@extends('layouts.app')

@section('content')
<div class="content-header pt-3 pb-2">
    <div class="container-fluid">
        <div class="row align-items-center">
            <div class="col-sm-6">
                <h1 class="m-0 font-weight-bold" style="font-size:1.5rem;">
                    <i class="fas fa-inbox mr-2 text-primary"></i> Kotak Masuk Notifikasi
                </h1>
                <p class="text-muted text-sm mb-0">Lihat semua pemberitahuan sistem dan peringatan stok.</p>
            </div>
            <div class="col-sm-6 text-right">
                <form action="{{ route('tenant.notifications.markAllRead', tenant('id')) }}" method="POST" class="d-inline">
                    @csrf
                    <button type="submit" class="btn btn-outline-primary btn-sm rounded-pill font-weight-bold">
                        <i class="fas fa-check-double mr-1"></i> Tandai Semua Dibaca
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<section class="content pt-2">
    <div class="container-fluid">
        <div class="card shadow-sm border-0" style="border-radius:10px;">
            <div class="card-body p-0">
                <ul class="list-group list-group-flush">
                    @forelse($notifications as $notification)
                        <li class="list-group-item {{ $notification->unread() ? 'bg-light border-left border-primary' : '' }}" style="border-left-width: 4px !important;">
                            <div class="d-flex align-items-center">
                                <div class="mr-3">
                                    <div class="icon-circle {{ $notification->unread() ? 'bg-primary' : 'bg-secondary' }} text-white d-flex align-items-center justify-content-center" style="width: 40px; height: 40px; border-radius: 50%;">
                                        <i class="{{ $notification->data['icon'] ?? 'fas fa-info-circle' }}"></i>
                                    </div>
                                </div>
                                <div class="flex-grow-1">
                                    <div class="d-flex justify-content-between">
                                        <h6 class="mb-1 {{ $notification->unread() ? 'font-weight-bold' : '' }}">
                                            {{ $notification->data['message'] }}
                                        </h6>
                                        <small class="text-muted">{{ $notification->created_at->diffForHumans() }}</small>
                                    </div>
                                    <p class="text-sm text-muted mb-0">Tipe: {{ ucfirst(str_replace('_', ' ', $notification->data['type'] ?? 'umum')) }}</p>
                                </div>
                                <div class="ml-3">
                                    <a href="{{ route('tenant.notifications.show', [tenant('id'), $notification->id]) }}" class="btn btn-link btn-sm text-primary p-0">
                                        <i class="fas fa-external-link-alt"></i> Lihat
                                    </a>
                                </div>
                            </div>
                        </li>
                    @empty
                        <li class="list-group-item text-center py-5">
                            <i class="fas fa-bell-slash fa-3x text-muted mb-3 d-block"></i>
                            <p class="text-muted">Tidak ada notifikasi untuk saat ini.</p>
                        </li>
                    @endforelse
                </ul>
            </div>
            @if($notifications->hasPages())
                <div class="card-footer bg-white pagination-sm">
                    {{ $notifications->links() }}
                </div>
            @endif
        </div>
    </div>
</section>
@endsection
