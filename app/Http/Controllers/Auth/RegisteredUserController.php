<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\Validation\ValidationException;
use App\Models\Tenant;
use App\Models\SubscriptionPlan;
use App\Models\PaymentMethod;
use App\Models\PromoCode;
use App\Models\Invoice;
use Illuminate\Support\Str;
use Stancl\Tenancy\Database\Models\Domain;
use Illuminate\View\View;
use Illuminate\Support\Facades\DB;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(Request $request): View
    {
        $plans = SubscriptionPlan::where('is_active', true)->orderBy('price', 'asc')->get();
        $selectedPlan = $request->query('plan');

        return view('auth.register', compact('plans', 'selectedPlan'));
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'dapur_name' => ['required', 'string', 'max:255'],
            'name'       => ['required', 'string', 'max:255'],
            'email'      => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'whatsapp'   => ['required', 'string', 'max:20'],
            'password'   => ['required', 'confirmed', Rules\Password::defaults()],
            'plan_id'    => ['required', 'exists:subscription_plans,id'],
            'promo_code' => ['nullable', 'string', 'exists:promo_codes,code'],
        ]);

        // CEK DOUBLE REGISTRATION
        if (User::where('email', $request->email)->exists()) {
            return back()->withInput()->withErrors(['email' => 'Email ini sudah terdaftar. Silakan login atau gunakan email lain.']);
        }

        $plan = SubscriptionPlan::findOrFail($request->plan_id);

        // 1. Generate Unique Slug for Tenant
        $baseId = Str::slug($request->dapur_name);
        $id = $baseId;
        $counter = 1;
        while (Tenant::where('id', $id)->exists()) {
            $id = $baseId . '-' . $counter++;
        }

        // 1.5 Ensure Directory exists before creation (Pre-initialization fix for hosting)
        $dbPath = storage_path("tenant_dbs/{$id}.sqlite");
        $dbDir = dirname($dbPath);
        if (!file_exists($dbDir)) {
            // Gunakan 0755 untuk kompatibilitas lebih baik di shared hosting
            mkdir($dbDir, 0755, true);
        }


        // 2. Heavy Lifting: Create Tenant & Domain FIRST (Outside Transaction to avoid lock timeouts during migrations)
        // This triggers database creation and migrations in Stancl Tenancy.
        $tenant = Tenant::create([
            'id' => $id,
            'name' => $request->dapur_name,
            'plan_id' => $plan->id,
            'plan_slug' => $plan->slug,
            'max_users' => $plan->max_users ?? 1,
            'max_members' => $plan->max_users ?? 1,
            'trial_ends_at' => null,
        ]);

        $centralDomain = parse_url(config('app.url'), PHP_URL_HOST) ?? $request->getHost();
        $tenant->domains()->create([
            'domain' => $id . '.' . $centralDomain
        ]);

        // 3. Central Transaction: Save lightweight records (Invoice, Central User, etc.)
        $userCentral = DB::transaction(function () use ($request, $plan, $tenant) {
            // Check Promo Code
            $promo = null;
            $discountAmount = 0;
            if ($request->promo_code) {
                $promo = PromoCode::where('code', $request->promo_code)
                    ->where('is_active', true)
                    ->where('starts_at', '<=', now())
                    ->where('ends_at', '>=', now())
                    ->where('used_count', '<', DB::raw('max_uses'))
                    ->first();

                if ($promo) {
                    if ($promo->type === 'percentage') {
                        $discountAmount = ($plan->price * $promo->value) / 100;
                    } else {
                        $discountAmount = min($promo->value, $plan->price);
                    }
                    $promo->increment('used_count');
                }
            }

            $finalAmount = max(0, $plan->price - $discountAmount);

            Invoice::create([
                'invoice_number'       => Invoice::generateNumber(),
                'tenant_id'            => $tenant->id,
                'subscription_plan_id' => $plan->id,
                'promo_code_id'        => $promo?->id,
                'base_amount'          => $plan->price,
                'discount_amount'      => $discountAmount,
                'final_amount'         => $finalAmount,
                'status'               => $plan->price > 0 ? 'pending' : 'paid',
                'due_date'             => now()->addDays(3),
                'paid_at'              => $plan->price > 0 ? null : now(),
                'payment_method'       => $plan->price > 0 ? 'manual_transfer' : 'free',
                'notes'                => 'Pendaftaran Akun Baru',
            ]);

            // PROVISION USER IN CENTRAL DB (For Global Login)
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'whatsapp' => $request->whatsapp,
                'password' => Hash::make($request->password),
                'tenant_id' => $tenant->id,
                'role' => 'admin',
            ]);

            // INITIALIZE TWO-WAY COMMUNICATION
            \App\Models\SupportTicket::create([
                'ticket_number' => \App\Models\SupportTicket::generateNumber(),
                'tenant_id' => $tenant->id,
                'user_id' => $user->id,
                'subject' => 'Selamat Datang di Dapur MBG!',
                'message' => "Halo {$request->name}, selamat bergabung! Silakan balas pesan ini jika ada kendala.",
                'priority' => 'medium',
                'status' => 'open',
                'last_replied_at' => now(),
            ]);

            return $user;
        });

        // 4. Finalizing: Initialize Tenancy & Create Local User in TENANT DB
        tenancy()->initialize($tenant);
        
        // PENTING: Gunakan DB facade dengan koneksi tenant, BUKAN User::query()
        // User model kini di-pin ke 'central' connection, jangan gunakan untuk DML tenant
        DB::connection('tenant')->table('users')->delete();

        DB::connection('tenant')->table('users')->insert([
            'name' => $request->name,
            'email' => $request->email,
            'whatsapp' => $request->whatsapp,
            'password' => Hash::make($request->password),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // End tenancy context and switch back to central before login
        tenancy()->end();

        event(new Registered($userCentral));
        Auth::login($userCentral);

        // Paket Gratis: Langsung ke Dashboard
        if ($plan->price <= 0 || $plan->price == '0.00' || $plan->price == 0) {
            session()->save(); // Simpan sesi eksplisit untuk hosting
            return redirect("/{$tenant->id}/dashboard")
                ->with('success', '🎉 Selamat datang! Akun Dapur Anda sudah aktif.');
        }

        // Paket Berbayar: Arahkan ke Halaman Pembayaran Khusus
        session()->save(); // PENTING: Simpan sesi eksplisit untuk hosting agar login tidak hilang

        return redirect("/{$tenant->id}/payment/pending")
            ->with('info', '🎉 Akun berhasil dibuat! Selesaikan pembayaran untuk mengaktifkan paket Anda.');

    }
}
