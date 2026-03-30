@extends('layouts.app')

@section('content')
<div class="content-header pt-3 pb-2">
    <div class="container-fluid">
        <div class="row align-items-center mb-2">
            <div class="col-sm-6">
                <h1 class="m-0 text-dark font-weight-bold" style="font-size: 1.8rem;">Edit Invoice</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right bg-transparent p-0 m-0">
                    <li class="breadcrumb-item"><a href="{{ url('super-admin/dashboard') }}"><i class="fas fa-home text-primary"></i> Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ url('super-admin/billing') }}">Billing</a></li>
                    <li class="breadcrumb-item"><a href="{{ url('super-admin/billing/' . $billing->id) }}">{{ $billing->invoice_number }}</a></li>
                    <li class="breadcrumb-item active">Edit</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-8">
                <div class="card card-primary card-outline shadow-sm">
                    <div class="card-header">
                        <h3 class="card-title font-weight-bold text-dark">
                            <i class="fas fa-edit text-primary mr-2"></i>
                            Perbarui Status Invoice: <span class="text-primary">{{ $billing->invoice_number }}</span>
                        </h3>
                    </div>
                    <div class="card-body">
                        {{-- Info Read-Only --}}
                        <div class="alert alert-light border mb-4">
                            <div class="row text-sm">
                                <div class="col-md-4"><strong>Cabang:</strong> {{ $billing->tenant_id }}</div>
                                <div class="col-md-4"><strong>Paket:</strong> {{ $billing->subscriptionPlan->name ?? '-' }}</div>
                                <div class="col-md-4"><strong>Total:</strong> Rp {{ number_format($billing->final_amount, 0, ',', '.') }}</div>
                            </div>
                        </div>

                        <form action="{{ url('/super-admin/billing/' . $billing->id) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group mb-4">
                                        <label class="font-weight-bold text-dark">Status Pembayaran <span class="text-danger">*</span></label>
                                        <select name="status" class="form-control @error('status') is-invalid @enderror" required>
                                            <option value="pending"   {{ old('status', $billing->status) === 'pending'   ? 'selected' : '' }}>⏳ Menunggu Bayar</option>
                                            <option value="paid"      {{ old('status', $billing->status) === 'paid'      ? 'selected' : '' }}>✅ Lunas</option>
                                            <option value="expired"   {{ old('status', $billing->status) === 'expired'   ? 'selected' : '' }}>❌ Kadaluarsa</option>
                                            <option value="cancelled" {{ old('status', $billing->status) === 'cancelled' ? 'selected' : '' }}>🚫 Dibatalkan</option>
                                        </select>
                                        @error('status')<span class="invalid-feedback">{{ $message }}</span>@enderror
                                        <small class="text-muted">Jika diubah ke <strong>Lunas</strong>, tanggal pembayaran otomatis diisi dengan waktu sekarang.</small>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group mb-4">
                                        <label class="font-weight-bold text-dark">Jatuh Tempo <span class="text-danger">*</span></label>
                                        <input type="date" name="due_date" class="form-control @error('due_date') is-invalid @enderror"
                                            value="{{ old('due_date', $billing->due_date->format('Y-m-d')) }}" required>
                                        @error('due_date')<span class="invalid-feedback">{{ $message }}</span>@enderror
                                    </div>
                                </div>
                            </div>

                            <div class="form-group mb-4">
                                <label class="font-weight-bold text-dark">Metode Pembayaran</label>
                                <select name="payment_method" class="form-control">
                                    <option value="">— Belum Dibayar —</option>
                                    @foreach(['Transfer Bank', 'QRIS', 'Virtual Account', 'Tunai', 'Dompet Digital', 'Kartu Kredit', 'Lainnya'] as $m)
                                        <option value="{{ $m }}" {{ old('payment_method', $billing->payment_method) === $m ? 'selected' : '' }}>{{ $m }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group mb-4">
                                <label class="font-weight-bold text-dark">Upload Bukti Pembayaran</label>
                                @if($billing->payment_proof)
                                    <div class="mb-2">
                                        <img src="{{ $billing->payment_proof }}" style="height: 80px; border-radius: 4px; border: 1px solid #dee2e6;">
                                        <small class="text-muted d-block">Bukti saat ini. Upload baru untuk mengganti.</small>
                                    </div>
                                @endif
                                <div class="custom-file">
                                    <input type="file" class="custom-file-input" name="payment_proof" id="payment_proof" accept="image/*">
                                    <label class="custom-file-label" for="payment_proof">Pilih gambar bukti bayar...</label>
                                </div>
                            </div>

                            <div class="form-group mb-5">
                                <label class="font-weight-bold text-dark">Catatan Admin</label>
                                <textarea name="notes" class="form-control" rows="3">{{ old('notes', $billing->notes) }}</textarea>
                            </div>

                            <div class="text-right">
                                <a href="{{ url('super-admin/billing/' . $billing->id) }}" class="btn btn-default px-4 mr-2">Batal</a>
                                <button type="submit" class="btn btn-primary px-4"><i class="fas fa-save mr-1"></i> Simpan Perubahan</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            {{-- PANEL RINGKASAN --}}
            <div class="col-lg-4">
                <div class="card shadow-sm border-warning">
                    <div class="card-header bg-warning text-white">
                        <h5 class="card-title font-weight-bold mb-0"><i class="fas fa-info-circle mr-2"></i> Ringkasan Invoice</h5>
                    </div>
                    <div class="card-body">
                        <table class="table table-sm table-borderless mb-0">
                            <tr>
                                <th class="text-muted" style="width: 45%">No. Invoice</th>
                                <td class="font-weight-bold">{{ $billing->invoice_number }}</td>
                            </tr>
                            <tr>
                                <th class="text-muted">Diterbitkan</th>
                                <td>{{ $billing->created_at->format('d M Y') }}</td>
                            </tr>
                            <tr>
                                <th class="text-muted">Paket</th>
                                <td>{{ $billing->subscriptionPlan->name ?? '-' }}</td>
                            </tr>
                            <tr>
                                <th class="text-muted">Harga Asli</th>
                                <td>Rp {{ number_format($billing->base_amount, 0, ',', '.') }}</td>
                            </tr>
                            @if($billing->discount_amount > 0)
                                <tr>
                                    <th class="text-muted">Diskon</th>
                                    <td class="text-danger">- Rp {{ number_format($billing->discount_amount, 0, ',', '.') }}</td>
                                </tr>
                            @endif
                            <tr class="border-top">
                                <th>Total Tagihan</th>
                                <td class="font-weight-bold text-primary">Rp {{ number_format($billing->final_amount, 0, ',', '.') }}</td>
                            </tr>
                            <tr>
                                <th class="text-muted">Status Saat Ini</th>
                                <td>{!! $billing->status_badge !!}</td>
                            </tr>
                            @if($billing->paid_at)
                                <tr>
                                    <th class="text-muted">Dibayar Pada</th>
                                    <td class="text-success">{{ $billing->paid_at->format('d M Y') }}</td>
                                </tr>
                            @endif
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

@push('js')
<script>
document.querySelector('#payment_proof').addEventListener('change', function() {
    document.querySelector('.custom-file-label').textContent = this.files[0] ? this.files[0].name : 'Pilih gambar bukti bayar...';
});
</script>
@endpush
@endsection
