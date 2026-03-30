@extends('layouts.app')

@push('css')
<style>
.chat-bubble {
    border-radius: 12px;
    padding: 12px 16px;
    max-width: 90%;
    position: relative;
}
.chat-staff { background: #e3f2fd; border-left: 4px solid #2196f3; }
.chat-tenant { background: #f1f8e9; border-left: 4px solid #66bb6a; }
.timeline { border-left: 2px solid #dee2e6; padding-left: 18px; }
</style>
@endpush

@section('content')
<div class="content-header pt-3 pb-2">
    <div class="container-fluid">
        <div class="row align-items-center">
            <div class="col-sm-7">
                <h1 class="m-0 font-weight-bold" style="font-size:1.4rem;">
                    <i class="fas fa-ticket-alt mr-2 text-primary"></i> #{{ $ticket->ticket_number }}
                </h1>
                <p class="text-muted text-sm mb-0">{{ $ticket->subject }}</p>
            </div>
            <div class="col-sm-5 text-right">
                {!! $ticket->status_badge !!}
                {!! $ticket->priority_badge !!}
                <a href="{{ route('tenant.support.index', tenant('id')) }}" class="btn btn-outline-secondary btn-sm ml-2">
                    <i class="fas fa-arrow-left"></i>
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

        <div class="row">
            <div class="col-md-8">
                {{-- Pesan Awal (Ticket) --}}
                <div class="card shadow-sm border-0 mb-3" style="border-radius:10px;">
                    <div class="card-header bg-white border-bottom py-3">
                        <h6 class="mb-0 font-weight-bold"><i class="fas fa-comment-dots mr-2 text-primary"></i>Percakapan Dukungan</h6>
                    </div>
                    <div class="card-body">
                        {{-- Pesan awal dari tenant --}}
                        <div class="d-flex mb-4">
                            <div class="mr-3 flex-shrink-0">
                                <div class="rounded-circle d-flex align-items-center justify-content-center font-weight-bold text-white"
                                     style="width:38px;height:38px;background:#66bb6a;font-size:.85rem;">
                                    T
                                </div>
                            </div>
                            <div class="flex-grow-1">
                                <div class="chat-bubble chat-tenant">
                                    <div class="font-weight-bold text-sm mb-1" style="color:#388e3c;">{{ tenant('name') ?? tenant('id') }}</div>
                                    <p class="mb-0 text-sm" style="white-space:pre-wrap;">{{ $ticket->message }}</p>
                                </div>
                                <small class="text-muted ml-1">{{ $ticket->created_at->format('d M Y H:i') }}</small>
                            </div>
                        </div>

                        <hr class="my-2">

                        {{-- Riwayat Balasan --}}
                        @forelse($ticket->replies as $reply)
                        <div class="d-flex mb-4 {{ $reply->is_staff ? '' : 'flex-row-reverse' }}">
                            <div class="{{ $reply->is_staff ? 'mr-3' : 'ml-3' }} flex-shrink-0">
                                <div class="rounded-circle d-flex align-items-center justify-content-center font-weight-bold text-white"
                                     style="width:38px;height:38px;background:{{ $reply->is_staff ? '#2196f3' : '#66bb6a' }};font-size:.85rem;">
                                    {{ $reply->is_staff ? 'S' : 'T' }}
                                </div>
                            </div>
                            <div class="{{ $reply->is_staff ? '' : 'text-right' }}" style="max-width:80%;">
                                <div class="chat-bubble {{ $reply->is_staff ? 'chat-staff' : 'chat-tenant' }}">
                                    <div class="font-weight-bold text-sm mb-1" style="color:{{ $reply->is_staff ? '#1565c0' : '#388e3c' }};">
                                        {{ $reply->is_staff ? 'Tim Dukungan MBG' : (tenant('name') ?? 'Anda') }}
                                    </div>
                                    <p class="mb-0 text-sm" style="white-space:pre-wrap;">{{ $reply->message }}</p>
                                </div>
                                <small class="text-muted {{ $reply->is_staff ? 'ml-1' : 'mr-1' }}">{{ $reply->created_at->format('d M Y H:i') }}</small>
                            </div>
                        </div>
                        @empty
                        <div class="text-center text-muted py-3">
                            <small>Tim kami belum merespons. Harap tunggu...</small>
                        </div>
                        @endforelse
                    </div>
                </div>

                {{-- Form Balas --}}
                @if($ticket->status !== 'closed')
                <div class="card shadow-sm border-0" style="border-radius:10px;">
                    <div class="card-body">
                        <h6 class="font-weight-bold mb-3"><i class="fas fa-reply mr-2 text-success"></i>Tambahkan Balasan</h6>
                        <form method="POST" action="{{ route('tenant.support.reply', [tenant('id'), $ticket->id]) }}">
                            @csrf
                            <div class="form-group">
                                <textarea name="message" rows="4" class="form-control"
                                    placeholder="Tulis balasan atau informasi tambahan disini..."></textarea>
                            </div>
                            <div class="text-right">
                                <button type="submit" class="btn btn-success">
                                    <i class="fas fa-paper-plane mr-1"></i> Kirim Balasan
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
                @else
                <div class="alert alert-secondary text-center">
                    <i class="fas fa-lock mr-1"></i> Tiket ini telah ditutup. Buat tiket baru jika masih ada pertanyaan.
                </div>
                @endif
            </div>

            {{-- Sidebar Info Tiket --}}
            <div class="col-md-4">
                <div class="card shadow-sm border-0" style="border-radius:10px;">
                    <div class="card-header bg-white border-bottom py-3">
                        <h6 class="mb-0 font-weight-bold">Informasi Tiket</h6>
                    </div>
                    <div class="card-body">
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted text-sm">Nomor</span>
                            <strong class="text-sm">{{ $ticket->ticket_number }}</strong>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted text-sm">Status</span>
                            {!! $ticket->status_badge !!}
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted text-sm">Prioritas</span>
                            {!! $ticket->priority_badge !!}
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted text-sm">Dibuat</span>
                            <strong class="text-sm">{{ $ticket->created_at->format('d M Y') }}</strong>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted text-sm">Total Balasan</span>
                            <strong class="text-sm">{{ $ticket->replies->count() }}</strong>
                        </div>
                        @if($ticket->last_replied_at)
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted text-sm">Respons Terakhir</span>
                            <strong class="text-sm">{{ $ticket->last_replied_at->diffForHumans() }}</strong>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
