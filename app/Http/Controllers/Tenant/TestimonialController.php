<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Models\Testimonial;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TestimonialController extends Controller
{
    /**
     * Tampilkan riwayat testimoni tenant ini.
     */
    public function index()
    {
        $testimonials = Testimonial::where('tenant_id', tenant('id'))
            ->orderBy('created_at', 'desc')
            ->get();
            
        return view('tenant.testimonials.index', compact('testimonials'));
    }

    /**
     * Simpan testimoni baru dari tenant.
     */
    public function store(Request $request)
    {
        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'content' => 'required|string|min:10',
            'image' => 'nullable|image|max:2048',
        ]);

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('testimonials', 'public');
        }

        Testimonial::create([
            'name' => Auth::user()->name,
            'user_id' => Auth::id(),
            'tenant_id' => tenant('id'),
            'content' => $request->input('content'),
            'rating' => $request->input('rating'),
            'image_url' => $imagePath,
            'is_active' => false, // Default nonaktif (perlu approval admin)
            'source' => 'tenant',
        ]);

        return redirect()->back()->with('success', 'Terima kasih! Testimoni Anda telah dikirim dan sedang menunggu kurasi oleh Super Admin.');
    }
}
