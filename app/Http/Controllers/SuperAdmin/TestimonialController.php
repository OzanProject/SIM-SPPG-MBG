<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Testimonial;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class TestimonialController extends Controller
{
    /**
     * Tampilkan daftar testimoni.
     */
    public function index()
    {
        $testimonials = Testimonial::orderBy('created_at', 'desc')->paginate(10);
        return view('super-admin.testimonials.index', compact('testimonials'));
    }

    /**
     * Simpan testimoni baru.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'content' => 'required|string',
            'rating' => 'required|integer|min:1|max:5',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $data = $request->except('image');
        $data['source'] = 'internal'; // Created by Super Admin
        $data['is_active'] = true;   // Default auto-active for internal admin entry

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('testimonials', 'public');
            $data['image_url'] = $path;
        }

        Testimonial::create($data);

        return redirect()->back()->with('success', 'Testimoni internal berhasil ditambahkan!');
    }

    /**
     * Update status aktif/nonaktif.
     */
    public function update(Request $request, Testimonial $testimonial)
    {
        $testimonial->update([
            'is_active' => !$testimonial->is_active
        ]);

        return redirect()->back()->with('success', 'Status testimoni berhasil diupdate!');
    }

    /**
     * Hapus testimoni.
     */
    public function destroy(Testimonial $testimonial)
    {
        if ($testimonial->image_url) {
            Storage::disk('public')->delete($testimonial->image_url);
        }
        
        $testimonial->delete();

        return redirect()->back()->with('success', 'Testimoni berhasil dihapus!');
    }
}
