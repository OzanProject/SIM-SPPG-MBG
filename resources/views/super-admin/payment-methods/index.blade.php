@extends('layouts.app')

@section('title', 'Manajemen Rekening Bank')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 font-weight-bold text-primary">Daftar Rekening Pembayaran</h5>
                    <a href="{{ route('super-admin.payment-methods.create') }}" class="btn btn-primary btn-sm rounded-pill">
                        <i class="fas fa-plus mr-1"></i> Tambah Rekening
                    </a>
                </div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    @endif

                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead class="bg-light">
                                <tr>
                                    <th>Bank / Platform</th>
                                    <th>Nomor Rekening</th>
                                    <th>Atas Nama</th>
                                    <th>Status</th>
                                    <th class="text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($paymentMethods as $pm)
                                    <tr>
                                        <td>
                                            <div class="font-weight-bold text-dark">{{ $pm->bank_name }}</div>
                                        </td>
                                        <td><code>{{ $pm->account_number }}</code></td>
                                        <td>{{ $pm->account_name }}</td>
                                        <td>
                                            @if($pm->is_active)
                                                <span class="badge badge-success">Aktif</span>
                                            @else
                                                <span class="badge badge-secondary">Non-Aktif</span>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            <a href="{{ route('super-admin.payment-methods.edit', $pm) }}" class="btn btn-sm btn-outline-info mr-1">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <form action="{{ route('super-admin.payment-methods.destroy', $pm) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus rekening ini?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-danger">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center py-4 text-muted">Belum ada rekening bank yang terdaftar.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
