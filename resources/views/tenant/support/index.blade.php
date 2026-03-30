@extends('layouts.app')

@push('css')
<style>
.ticket-row:hover { background: #f8f9fa; }
.badge-status-open { background: #28a745; color: #fff; }
.badge-status-pending { background: #ffc107; color: #333; }
.badge-status-closed { background: #6c757d; color: #fff; }
.stat-pill {
    border-radius: 12px;
    padding: 14px 20px;
    text-align: center;
    box-shadow: 0 2px 8px rgba(0,0,0,0.07);
}
</style>
@endpush

@section('content')
<div class="content-header pt-3 pb-2">
    <div class="container-fluid">
        <div class="row align-items-center">
            <div class="col-sm-6">
                <h1 class="m-0 font-weight-bold" style="font-size:1.5rem;">
                    <i class="fas fa-headset mr-2 text-primary"></i> Pusat Dukungan
                </h1>
                <p class="text-muted text-sm mb-0">Ajukan pertanyaan atau laporan teknis kepada tim kami.</p>
            </div>
            <div class="col-sm-6 text-right">
                <a href="{{ route('tenant.support.create', tenant('id')) }}" class="btn btn-primary btn-sm">
                    <i class="fas fa-plus mr-1"></i> Buat Tiket Baru
                </a>
            </div>
        </div>
    </div>
</div>

<section class="content pt-2">
    <div class="container-fluid">

        @if(session('success'))
            <div class="alert alert-success alert-dismissible">
                <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
                <i class="fas fa-check-circle mr-1"></i> {{ session('success') }}
            </div>
        @endif

        {{-- Stat Row --}}
        <div class="row mb-3">
            <div class="col-6 col-md-3 mb-2">
                <div class="stat-pill bg-white">
                    <div class="text-2xl font-weight-bold">{{ $stats['total'] }}</div>
                    <small class="text-muted">Total Tiket</small>
                </div>
            </div>
            <div class="col-6 col-md-3 mb-2">
                <div class="stat-pill bg-white">
                    <div class="text-2xl font-weight-bold text-success">{{ $stats['open'] }}</div>
                    <small class="text-muted">Terbuka</small>
                </div>
            </div>
            <div class="col-6 col-md-3 mb-2">
                <div class="stat-pill bg-white">
                    <div class="text-2xl font-weight-bold text-warning">{{ $stats['pending'] }}</div>
                    <small class="text-muted">Diproses</small>
                </div>
            </div>
            <div class="col-6 col-md-3 mb-2">
                <div class="stat-pill bg-white">
                    <div class="text-2xl font-weight-bold text-secondary">{{ $stats['closed'] }}</div>
                    <small class="text-muted">Selesai</small>
                </div>
            </div>
        </div>

        {{-- Ticket List --}}
        <div class="card shadow-sm border-0" style="border-radius:10px;">
            <div class="card-body p-0">
                @forelse($tickets as $ticket)
                <a href="{{ route('tenant.support.show', [tenant('id'), $ticket->id]) }}" class="text-reset text-decoration-none">
                    <div class="d-flex align-items-center px-4 py-3 border-bottom ticket-row">
                        <div class="mr-3">
                            @if($ticket->status === 'open')
                                <span class="badge badge-status-open px-2 py-1" style="border-radius:20px;font-size:.7rem;">Terbuka</span>
                            @elseif($ticket->status === 'pending')
                                <span class="badge badge-status-pending px-2 py-1" style="border-radius:20px;font-size:.7rem;">Diproses</span>
                            @else
                                <span class="badge badge-status-closed px-2 py-1" style="border-radius:20px;font-size:.7rem;">Selesai</span>
                            @endif
                        </div>
                        <div class="flex-grow-1">
                            <div class="font-weight-bold">{{ $ticket->subject }}</div>
                            <small class="text-muted">#{{ $ticket->ticket_number }} &middot; {{ $ticket->created_at->diffForHumans() }}</small>
                        </div>
                        <div class="text-right">
                            {!! $ticket->priority_badge !!}
                            <div class="text-muted text-xs mt-1">{{ $ticket->replies_count }} balasan</div>
                        </div>
                    </div>
                </a>
                @empty
                <div class="text-center py-5 text-muted">
                    <i class="fas fa-ticket-alt fa-3x mb-3 text-light"></i>
                    <p>Anda belum memiliki tiket dukungan.</p>
                    <a href="{{ route('tenant.support.create', tenant('id')) }}" class="btn btn-primary btn-sm">
                        <i class="fas fa-plus mr-1"></i> Buat Tiket Pertama
                    </a>
                </div>
                @endforelse
            </div>
        </div>

    </div>
</section>
@endsection
