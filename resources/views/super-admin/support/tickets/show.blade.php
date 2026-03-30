@extends('layouts.app')

@push('css')
<style>
    .direct-chat-messages { height: 400px; padding: 20px; }
    .direct-chat-text { background-color: #f0f2f5; border: none; border-radius: 15px; padding: 10px 15px; color: #1c1e21; position: relative; }
    .right .direct-chat-text { background-color: #0084ff; color: #fff; }
    .direct-chat-info { margin-bottom: 5px; }
    .direct-chat-timestamp { font-size: 0.75rem; }
    .card-ticket-info { border-left: 4px solid #007bff; }
</style>
@endpush

@section('content')
<div class="content-header pt-3 pb-2">
    <div class="container-fluid">
        <div class="row align-items-center mb-2">
            <div class="col-sm-6">
                <h1 class="m-0 font-weight-bold" style="font-size: 1.5rem;">Detail Tiket: {{ $ticket->ticket_number }}</h1>
            </div>
            <div class="col-sm-6 text-right">
                <a href="{{ route('support.tickets.index') }}" class="btn btn-sm btn-secondary shadow-sm"><i class="fas fa-arrow-left mr-1"></i> Kembali</a>
            </div>
        </div>
    </div>
</div>

<section class="content">
    <div class="container-fluid">
        <div class="row">
            {{-- KOLOM KIRI: Informasi Tiket --}}
            <div class="col-md-4">
                <div class="card shadow-sm card-ticket-info">
                    <div class="card-header bg-white"><h5 class="card-title font-weight-bold">Informasi Tiket</h5></div>
                    <div class="card-body p-0">
                        <table class="table table-sm mb-0">
                            <tr>
                                <td class="pl-3 py-2 text-muted">Cabang:</td>
                                <td class="pr-3 text-right font-weight-bold">{{ $ticket->tenant_id }}</td>
                            </tr>
                            <tr>
                                <td class="pl-3 py-2 text-muted">Pelapor:</td>
                                <td class="pr-3 text-right font-weight-bold">{{ $ticket->user->name ?? 'User' }}</td>
                            </tr>
                            <tr>
                                <td class="pl-3 py-2 text-muted">Prioritas:</td>
                                <td class="pr-3 text-right">{!! $ticket->priority_badge !!}</td>
                            </tr>
                            <tr>
                                <td class="pl-3 py-2 text-muted">Status:</td>
                                <td class="pr-3 text-right text-uppercase font-weight-bold">
                                    <form action="{{ route('support.tickets.status', $ticket) }}" method="POST" class="d-inline">
                                        @csrf @method('PATCH')
                                        <select name="status" class="form-control form-control-sm d-inline-block w-auto" onchange="this.form.submit()">
                                            <option value="open" {{ $ticket->status == 'open' ? 'selected' : '' }}>Terbuka</option>
                                            <option value="pending" {{ $ticket->status == 'pending' ? 'selected' : '' }}>Diproses</option>
                                            <option value="closed" {{ $ticket->status == 'closed' ? 'selected' : '' }}>Selesai</option>
                                        </select>
                                    </form>
                                </td>
                            </tr>
                            <tr>
                                <td class="pl-3 py-2 text-muted">Dibuat:</td>
                                <td class="pr-3 text-right font-weight-bold">{{ $ticket->created_at->format('d M Y H:i') }}</td>
                            </tr>
                        </table>
                    </div>
                </div>

                <div class="card shadow-sm mt-3">
                    <div class="card-header bg-white"><h5 class="card-title font-weight-bold">Subjek & Pesan Awal</h5></div>
                    <div class="card-body">
                        <h6 class="font-weight-bold mb-3">{{ $ticket->subject }}</h6>
                        <p class="text-muted" style="white-space: pre-wrap;">{{ $ticket->message }}</p>
                    </div>
                </div>
            </div>

            {{-- KOLOM KANAN: Chat Percakapan --}}
            <div class="col-md-8">
                <div class="card direct-chat direct-chat-primary shadow-sm border-0">
                    <div class="card-header bg-white py-3">
                        <h3 class="card-title font-weight-bold text-dark">Percakapan Bantuan</h3>
                    </div>
                    <div class="card-body">
                        <div class="direct-chat-messages">
                            {{-- Pesan asli dari pembuka tiket (tampil paling atas jika perlu, tapi kita anggap sebagai flow percakapan) --}}
                            
                            @foreach($ticket->replies as $reply)
                                <div class="direct-chat-msg {{ $reply->is_staff ? 'right' : '' }}">
                                    <div class="direct-chat-info clearfix">
                                        <span class="direct-chat-name float-{{ $reply->is_staff ? 'right' : 'left' }} text-sm font-weight-bold">
                                            {{ $reply->user->name }} {{ $reply->is_staff ? '(Support)' : '' }}
                                        </span>
                                        <span class="direct-chat-timestamp float-{{ $reply->is_staff ? 'left' : 'right' }} text-muted">
                                            {{ $reply->created_at->format('d M H:i') }}
                                        </span>
                                    </div>
                                    <div class="direct-chat-text shadow-sm border-0">
                                        {{ $reply->message }}
                                    </div>
                                </div>
                            @endforeach

                            @if($ticket->replies->isEmpty())
                                <div class="text-center py-5">
                                    <i class="fas fa-comments fa-3x text-light mb-3"></i>
                                    <p class="text-muted">Belum ada balasan untuk tiket ini.</p>
                                </div>
                            @endif
                        </div>
                    </div>
                    <div class="card-footer bg-white py-3">
                        @if($ticket->status != 'closed')
                            <form action="{{ route('support.tickets.reply', $ticket) }}" method="POST">
                                @csrf
                                <div class="input-group">
                                    <textarea name="message" placeholder="Tulis balasan di sini..." class="form-control mr-2" rows="2" style="border-radius: 10px;" required></textarea>
                                    <span class="input-group-append align-items-end">
                                        <button type="submit" class="btn btn-primary" style="border-radius: 10px; height: auto;"><i class="fas fa-paper-plane mr-1"></i> Balas</button>
                                    </span>
                                </div>
                                <div class="mt-2 text-sm text-muted">
                                    <label class="mr-3"><input type="radio" name="status" value="pending" {{ $ticket->status == 'pending' ? 'checked' : '' }}> Biarkan Diproses</label>
                                    <label><input type="radio" name="status" value="closed"> Tandai Selesai</label>
                                </div>
                            </form>
                        @else
                            <div class="alert alert-secondary text-center mb-0">
                                <i class="fas fa-lock mr-2"></i> Tiket ini telah ditutup. Buka kembali status Tiket di panel kiri untuk membalas.
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
