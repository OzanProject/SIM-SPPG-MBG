<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\PaymentMethod;
use Illuminate\Http\Request;

class PaymentMethodController extends Controller
{
    public function index()
    {
        $paymentMethods = PaymentMethod::orderBy('bank_name')->get();
        return view('super-admin.payment-methods.index', compact('paymentMethods'));
    }

    public function create()
    {
        return view('super-admin.payment-methods.form', [
            'paymentMethod' => new PaymentMethod(),
            'method' => 'POST',
            'action' => route('super-admin.payment-methods.store')
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'bank_name' => 'required|string|max:255',
            'account_number' => 'required|string|max:255',
            'account_name' => 'required|string|max:255',
            'instructions' => 'nullable|string',
            'is_active' => 'boolean'
        ]);

        PaymentMethod::create($request->all());

        return redirect()->route('super-admin.payment-methods.index')
            ->with('success', 'Rekening bank berhasil ditambahkan.');
    }

    public function edit(PaymentMethod $paymentMethod)
    {
        return view('super-admin.payment-methods.form', [
            'paymentMethod' => $paymentMethod,
            'method' => 'PUT',
            'action' => route('super-admin.payment-methods.update', $paymentMethod)
        ]);
    }

    public function update(Request $request, PaymentMethod $paymentMethod)
    {
        $request->validate([
            'bank_name' => 'required|string|max:255',
            'account_number' => 'required|string|max:255',
            'account_name' => 'required|string|max:255',
            'instructions' => 'nullable|string',
            'is_active' => 'boolean'
        ]);

        $data = $request->all();
        $data['is_active'] = $request->has('is_active');
        
        $paymentMethod->update($data);

        return redirect()->route('super-admin.payment-methods.index')
            ->with('success', 'Rekening bank diperbarui.');
    }

    public function destroy(PaymentMethod $paymentMethod)
    {
        $paymentMethod->delete();
        return back()->with('success', 'Rekening bank dihapus.');
    }
}
