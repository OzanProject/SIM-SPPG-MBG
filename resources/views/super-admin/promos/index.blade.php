@extends('layouts.app')

@section('content')
<div class="content-header pt-3 pb-2">
    <div class="container-fluid">
        <div class="row align-items-center mb-2">
            <div class="col-sm-6">
                <h1 class="m-0 text-dark font-weight-bold" style="font-size: 1.8rem;">Kelola Diskon</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right bg-transparent p-0 m-0">
                    <li class="breadcrumb-item"><a href="{{ url('super-admin/dashboard') }}"><i class="fas fa-home text-primary"></i> Dashboard</a></li>
                    <li class="breadcrumb-item active">Kelola Diskon</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<section class="content">
    <div class="container-fluid">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show">
                <button type="button" class="close" data-dismiss="alert">&times;</button>
                <i class="fas fa-check-circle mr-1"></i> {{ session('success') }}
            </div>
        @endif

        <div class="card shadow-sm">
            <div class="card-header d-flex align-items-center">
                <h3 class="card-title font-weight-bold text-dark mb-0">Daftar Kode Promo</h3>
                <div class="ml-auto">
                    <a href="{{ url('/super-admin/promos/create') }}" class="btn btn-primary font-weight-bold shadow-sm">
                        <i class="fas fa-plus mr-1"></i> Buat Kode Promo
                    </a>
                </div>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th class="pl-4">Kode</th>
                                <th>Tipe</th>
                                <th>Nilai</th>
                                <th>Masa Berlaku</th>
                                <th class="text-center">Kuota</th>
                                <th>Berlaku Untuk Paket</th>
                                <th class="text-center">Status</th>
                                <th class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($promos as $promo)
                                <tr>
                                    <td class="pl-4 font-weight-bold text-dark" style="letter-spacing: 1px;">{{ $promo->code }}</td>
                                    <td>
                                        @if($promo->type === 'fixed')
                                            <span class="badge px-3 py-1 font-weight-bold" style="background:#2196f3; color:#fff; border-radius:4px;">Fixed</span>
                                        @else
                                            <span class="badge px-3 py-1 font-weight-bold" style="background:#ff9800; color:#fff; border-radius:4px;">Percent</span>
                                        @endif
                                    </td>
                                    <td class="font-weight-bold">
                                        @if($promo->type === 'fixed')
                                            Rp {{ number_format($promo->value, 0, ',', '.') }}
                                        @else
                                            {{ $promo->value }}%
                                        @endif
                                    </td>
                                    <td class="text-muted" style="font-size: 0.9rem;">
                                        {{ $promo->starts_at->format('d M Y') }} &ndash; {{ $promo->ends_at->format('d M Y') }}
                                    </td>
                                    <td class="text-center">
                                        <span class="{{ $promo->remaining_uses == 0 ? 'text-danger' : 'text-dark' }} font-weight-bold">
                                            {{ $promo->used_count }} / {{ $promo->max_uses }}
                                        </span>
                                    </td>
                                    <td>
                                        @if($promo->subscriptionPlans->isEmpty())
                                            <span class="text-muted font-italic" style="font-size: 0.85rem;">Semua Paket</span>
                                        @else
                                            @foreach($promo->subscriptionPlans as $plan)
                                                <span class="badge badge-secondary font-weight-normal mr-1">{{ $plan->name }}</span>
                                            @endforeach
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        @if($promo->is_active && $promo->isValid())
                                            <span class="badge px-3 py-1" style="background:#28a745; color:#fff; border-radius:12px; font-size:0.75rem;">Active</span>
                                        @elseif($promo->is_active && !$promo->isValid())
                                            <span class="badge px-3 py-1" style="background:#dc3545; color:#fff; border-radius:12px; font-size:0.75rem;">Expired</span>
                                        @else
                                            <span class="badge px-3 py-1" style="background:#6c757d; color:#fff; border-radius:12px; font-size:0.75rem;">Nonaktif</span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        <a href="{{ url('/super-admin/promos/' . $promo->id . '/edit') }}" class="btn btn-warning btn-sm" title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ url('/super-admin/promos/' . $promo->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus kode promo \'{{ $promo->code }}\' secara permanen?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm" title="Hapus">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center py-5">
                                        <i class="fas fa-percent mb-3 text-muted" style="font-size: 3rem; display: block;"></i>
                                        <p class="text-muted mb-3">Belum ada kode promo. Buat satu untuk menarik lebih banyak koperasi bergabung!</p>
                                        <a href="{{ url('/super-admin/promos/create') }}" class="btn btn-primary btn-sm"><i class="fas fa-plus mr-1"></i> Buat Kode Promo</a>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
