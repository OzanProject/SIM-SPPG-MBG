@extends('layouts.app')

@section('content')
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0 font-weight-bold"><i class="fas fa-life-ring mr-2 text-primary"></i> Tiket Bantuan</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ url('super-admin/dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active">Tiket Bantuan</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<section class="content">
    <div class="container-fluid">
        {{-- Statistik Singkat --}}
        <div class="row">
            <div class="col-lg-3 col-6">
                <div class="small-box bg-info shadow-sm">
                    <div class="inner"><h3>{{ $stats['total'] }}</h3><p>Total Tiket</p></div>
                    <div class="icon"><i class="fas fa-ticket-alt"></i></div>
                </div>
            </div>
            <div class="col-lg-3 col-6">
                <div class="small-box bg-success shadow-sm">
                    <div class="inner"><h3>{{ $stats['open'] }}</h3><p>Tiket Terbuka</p></div>
                    <div class="icon"><i class="fas fa-envelope-open"></i></div>
                </div>
            </div>
            <div class="col-lg-3 col-6">
                <div class="small-box bg-warning shadow-sm">
                    <div class="inner"><h3>{{ $stats['pending'] }}</h3><p>Sedang Diproses</p></div>
                    <div class="icon"><i class="fas fa-clock"></i></div>
                </div>
            </div>
            <div class="col-lg-3 col-6">
                <div class="small-box bg-secondary shadow-sm">
                    <div class="inner"><h3>{{ $stats['closed'] }}</h3><p>Selesai</p></div>
                    <div class="icon"><i class="fas fa-check-double"></i></div>
                </div>
            </div>
        </div>

        {{-- Filter & Tabel --}}
        <div class="card shadow-sm border-0">
            <div class="card-header bg-white py-3">
                <form action="{{ route('support.tickets.index') }}" method="GET" class="row">
                    <div class="col-md-3">
                        <select name="status" class="form-control form-control-sm" onchange="this.form.submit()">
                            <option value="">-- Semua Status --</option>
                            <option value="open" {{ request('status') == 'open' ? 'selected' : '' }}>Terbuka</option>
                            <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Diproses</option>
                            <option value="closed" {{ request('status') == 'closed' ? 'selected' : '' }}>Selesai</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <select name="tenant_id" class="form-control form-control-sm" onchange="this.form.submit()">
                            <option value="">-- Semua Cabang --</option>
                            @foreach($tenants as $tenant)
                                <option value="{{ $tenant->id }}" {{ request('tenant_id') == $tenant->id ? 'selected' : '' }}>{{ $tenant->id }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <select name="priority" class="form-control form-control-sm" onchange="this.form.submit()">
                            <option value="">-- Semua Prioritas --</option>
                            <option value="low" {{ request('priority') == 'low' ? 'selected' : '' }}>Rendah</option>
                            <option value="medium" {{ request('priority') == 'medium' ? 'selected' : '' }}>Sedang</option>
                            <option value="high" {{ request('priority') == 'high' ? 'selected' : '' }}>Tinggi</option>
                            <option value="urgent" {{ request('priority') == 'urgent' ? 'selected' : '' }}>Mendesak</option>
                        </select>
                    </div>
                    <div class="col-md-3 text-right">
                        <a href="{{ route('support.tickets.index') }}" class="btn btn-sm btn-default"><i class="fas fa-sync-alt"></i> Reset</a>
                    </div>
                </form>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th class="pl-4">No. Tiket</th>
                                <th>Cabang</th>
                                <th>Subjek</th>
                                <th>Prioritas</th>
                                <th>Status</th>
                                <th>Update Terakhir</th>
                                <th class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($tickets as $ticket)
                                <tr>
                                    <td class="pl-4 font-weight-bold">{{ $ticket->ticket_number }}</td>
                                    <td><span class="badge badge-outline-secondary">{{ $ticket->tenant_id }}</span></td>
                                    <td>{{ $ticket->subject }}</td>
                                    <td>{!! $ticket->priority_badge !!}</td>
                                    <td>{!! $ticket->status_badge !!}</td>
                                    <td class="text-sm">
                                        {{ $ticket->last_replied_at ? $ticket->last_replied_at->diffForHumans() : $ticket->created_at->diffForHumans() }}
                                    </td>
                                    <td class="text-center">
                                        <a href="{{ route('support.tickets.show', $ticket) }}" class="btn btn-xs btn-primary shadow-sm"><i class="fas fa-eye mr-1"></i> Detail</a>
                                        <form action="{{ route('support.tickets.destroy', $ticket) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus tiket ini?')">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="btn btn-xs btn-danger shadow-sm"><i class="fas fa-trash"></i></button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center py-5">
                                        <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                                        <p class="text-muted">Tidak ada tiket bantuan yang ditemukan.</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="card-footer bg-white py-3">
                {{ $tickets->links() }}
            </div>
        </div>
    </div>
</section>
@endsection
