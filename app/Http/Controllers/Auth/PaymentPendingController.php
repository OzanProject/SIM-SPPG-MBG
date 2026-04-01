<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use App\Models\PaymentMethod;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PaymentPendingController extends Controller
{
    /**
     * Tampilkan halaman pembayaran untuk invoice pending terbaru tenant.
     */
    public function show(Request $request)
    {
        // Ambil invoice pending terbaru milik tenant ini via CENTRAL DB
        // WAJIB gunakan ->on('central') karena tenancy sudah aktif (koneksi default = tenant DB)
        $invoice = Invoice::on('central')
            ->where('tenant_id', tenant('id'))
            ->where('status', 'pending')
            ->with('subscriptionPlan')
            ->latest()
            ->first();

        if (!$invoice) {
            return redirect('/'. tenant('id') . '/dashboard')
                ->with('info', 'Tidak ada tagihan yang menunggu pembayaran.');
        }

        // Ambil metode pembayaran aktif dari CENTRAL DB (bukan tenant DB)
        $paymentMethods = PaymentMethod::on('central')->where('is_active', true)->get();

        return view('auth.payment-pending', compact('invoice', 'paymentMethods'));
    }

    /**
     * Terima upload bukti bayar dari pengguna.
     */
    public function uploadProof(Request $request)
    {
        $request->validate([
            'payment_proof' => ['required', 'image', 'mimes:jpeg,png,jpg,webp', 'max:3072'],
            // PENTING: Gunakan prefix 'central.' agar validasi exists juga menggunakan central DB
            'invoice_id'    => ['required', 'exists:central.invoices,id'],
        ]);

        $invoice = Invoice::on('central')->findOrFail($request->invoice_id);

        // Pastikan invoice ini milik tenant yang sedang aktif
        if ($invoice->tenant_id !== tenant('id')) {
            abort(403, 'Akses ditolak.');
        }

        // Hapus bukti lama jika ada
        if ($invoice->payment_proof) {
            $oldPath = str_replace('/storage/', '', $invoice->payment_proof);
            Storage::disk('public')->delete($oldPath);
        }

        // Simpan bukti baru ke disk central agar tersentralisasi
        $path = $request->file('payment_proof')->store('payment-proofs', 'central');
        $invoice->update([
            'payment_proof' => $path,
        ]);

        return redirect('/'. tenant('id') . '/payment/pending')
            ->with('success', '✅ Bukti pembayaran berhasil dikirim! Admin akan memverifikasi dalam 1x24 jam. Anda akan dinotifikasi via WhatsApp saat akun diaktifkan.');
    }
}
