<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\CustomPage;
use Illuminate\Http\Request;

class CustomPageController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $pages = CustomPage::orderBy('id', 'desc')->get();
        return view('super-admin.custom-pages.index', compact('pages'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('super-admin.custom-pages.form');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title'          => 'required|max:255',
            'slug'           => 'nullable|max:255|unique:custom_pages,slug',
            'content'        => 'nullable',
            'is_active'      => 'boolean',
            'show_in_footer' => 'boolean',
        ]);

        $validated['is_active'] = $request->has('is_active');
        $validated['show_in_footer'] = $request->has('show_in_footer');

        CustomPage::create($validated);

        return redirect()->route('custom-pages.index')->with('success', 'Halaman kustom berhasil ditambahkan.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(CustomPage $customPage)
    {
        return view('super-admin.custom-pages.form', compact('customPage'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, CustomPage $customPage)
    {
        $validated = $request->validate([
            'title'          => 'required|max:255',
            'slug'           => 'nullable|max:255|unique:custom_pages,slug,' . $customPage->id,
            'content'        => 'nullable',
            'is_active'      => 'boolean',
            'show_in_footer' => 'boolean',
        ]);

        $validated['is_active'] = $request->has('is_active');
        $validated['show_in_footer'] = $request->has('show_in_footer');

        $customPage->update($validated);

        return redirect()->route('custom-pages.index')->with('success', 'Halaman kustom berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(CustomPage $customPage)
    {
        $customPage->delete();
        return redirect()->route('custom-pages.index')->with('success', 'Halaman kustom berhasil dihapus.');
    }
}
