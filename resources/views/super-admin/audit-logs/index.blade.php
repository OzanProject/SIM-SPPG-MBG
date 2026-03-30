@extends('layouts.app')

@section('title', 'Global Audit Log')

@section('content')
<div class="content-header pt-3 pb-2">
    <div class="container-fluid">
        <div class="row align-items-center">
            <div class="col-sm-6">
                <h1 class="m-0 text-dark font-weight-bold" style="font-size: 1.8rem;">Global Audit Log</h1>
                <p class="text-muted mb-0">Jejak aktivitas sistem terpusat dan pergerakan kritis.</p>
            </div>
          <div class="col-sm-6 text-right">
             <ol class="breadcrumb float-sm-right bg-transparent p-0 mb-0">
               <li class="breadcrumb-item"><a href="{{ url('/super-admin/dashboard') }}" class="text-primary">Dashboard</a></li>
               <li class="breadcrumb-item active">Audit Log</li>
             </ol>
          </div>
        </div>
    </div>
</div>

    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
        
        <div class="card card-outline card-primary shadow-sm rounded-lg border-0">
            <div class="card-header bg-white border-bottom">
                <form action="{{ route('super-admin.audit-logs.index') }}" method="GET" class="form-inline">
                    <div class="form-group mr-2">
                        <select name="action" class="form-control form-control-sm border-gray-300">
                            <option value="">-- Semua Aksi --</option>
                            <option value="login" {{ request('action') == 'login' ? 'selected' : '' }}>Login Sukses</option>
                            <option value="login_failed" {{ request('action') == 'login_failed' ? 'selected' : '' }}>Login Gagal</option>
                            <option value="created" {{ request('action') == 'created' ? 'selected' : '' }}>Data Dibuat (Created)</option>
                            <option value="updated" {{ request('action') == 'updated' ? 'selected' : '' }}>Data Diubah (Updated)</option>
                            <option value="deleted" {{ request('action') == 'deleted' ? 'selected' : '' }}>Data Dihapus (Deleted)</option>
                        </select>
                    </div>
                    <div class="form-group mr-2">
                        <input type="text" name="module" class="form-control form-control-sm border-gray-300" placeholder="Filter Modul (misal: Tenant)" value="{{ request('module') }}">
                    </div>
                    <button type="submit" class="btn btn-sm btn-primary px-3 rounded-pill"><i class="fas fa-filter mr-1"></i> Filter</button>
                    @if(request()->has('action') || request()->has('module'))
                        <a href="{{ route('super-admin.audit-logs.index') }}" class="btn btn-sm btn-default px-3 rounded-pill ml-2 text-muted">Clear</a>
                    @endif
                </form>
            </div>
            
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover table-striped mb-0 text-sm">
                        <thead class="bg-light text-muted">
                            <tr>
                                <th width="12%" class="pl-4">Waktu</th>
                                <th width="15%">Pengguna</th>
                                <th width="10%">Tipe Aksi</th>
                                <th width="18%">Target Modul / ID</th>
                                <th width="35%">Ringkasan Perubahan</th>
                                <th width="10%" class="text-center">Perangkat</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($logs as $log)
                            <tr>
                                <td class="pl-4 font-weight-medium text-gray-800">{{ $log->created_at->format('d M Y, H:i:s') }}</td>
                                <td>
                                    @if($log->user)
                                        <div class="d-flex align-items-center">
                                            <div class="mr-2">
                                                <i class="fas fa-user-circle text-muted fa-lg"></i>
                                            </div>
                                            <div>
                                                <span class="d-block font-weight-bold">{{ $log->user->name }}</span>
                                                <small class="text-muted">{{ $log->user->email }}</small>
                                            </div>
                                        </div>
                                    @else
                                        <span class="text-muted font-italic">Guest / Sistem</span>
                                    @endif
                                </td>
                                <td>
                                    @if($log->action == 'created')
                                        <span class="badge badge-success px-2 py-1"><i class="fas fa-plus-circle mr-1"></i> Created</span>
                                    @elseif($log->action == 'updated')
                                        <span class="badge badge-warning text-dark px-2 py-1"><i class="fas fa-edit mr-1"></i> Updated</span>
                                    @elseif($log->action == 'deleted')
                                        <span class="badge badge-danger px-2 py-1"><i class="fas fa-trash mr-1"></i> Deleted</span>
                                    @elseif($log->action == 'login')
                                        <span class="badge badge-info px-2 py-1"><i class="fas fa-sign-in-alt mr-1"></i> Login</span>
                                    @elseif($log->action == 'login_failed')
                                        <span class="badge badge-dark px-2 py-1"><i class="fas fa-user-lock mr-1"></i> Failed</span>
                                    @else
                                        <span class="badge badge-secondary px-2 py-1">{{ strtoupper($log->action) }}</span>
                                    @endif
                                </td>
                                <td>
                                    @php
                                        $modelName = class_basename($log->model_type) ?: '-';
                                        $modelIcon = match(strtolower($modelName)) {
                                            'user' => 'fas fa-user-circle text-primary',
                                            'tenant' => 'fas fa-store text-success',
                                            'subscriptionplan' => 'fas fa-box text-warning',
                                            'invoice' => 'fas fa-file-invoice-dollar text-info',
                                            default => 'fas fa-database text-secondary'
                                        };
                                    @endphp
                                    <div class="d-flex align-items-center">
                                        <div class="mr-2"><i class="{{ $modelIcon }} fa-lg"></i></div>
                                        <div>
                                            <span class="d-block font-weight-bold text-dark">{{ $modelName == 'SubscriptionPlan' ? 'Paket Langganan' : ($modelName == 'Tenant' ? 'Dapur (Cabang)' : $modelName) }}</span>
                                            <small class="text-muted"><i class="fas fa-fingerprint mr-1"></i> ID: {{ $log->model_id ?: '-' }}</small>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    @if($log->changes && is_array($log->changes))
                                        <div class="border rounded bg-white p-2" style="max-height: 120px; overflow-y: auto; font-size: 0.75rem;">
                                            @foreach($log->changes as $key => $val)
                                                @php
                                                    $valStr = is_string($val) ? Str::limit($val, 40) : json_encode($val);
                                                    if(in_array($key, ['created_at', 'updated_at', 'remember_token', 'password'])) continue;
                                                @endphp
                                                <div class="mb-1 border-bottom pb-1 last-border-0">
                                                    <span class="text-dark font-weight-bold">{{ strtoupper(str_replace('_', ' ', $key)) }}</span>: 
                                                    <span class="text-muted">{{ $valStr ?: '(Kosong)' }}</span>
                                                </div>
                                            @endforeach
                                        </div>
                                    @else
                                        <span class="badge badge-light text-muted border px-2 py-1"><i class="fas fa-info-circle mr-1"></i>Tidak ada detail spesifik.</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <span class="d-inline-block badge badge-light border text-dark mb-1 font-weight-normal" title="{{ $log->ip_address }}">
                                        {{ $log->ip_address }}
                                    </span>
                                    <div class="mt-1">
                                        @if(Str::contains(strtolower($log->user_agent), 'windows'))
                                            <i class="fab fa-windows text-info" title="Windows"></i>
                                        @elseif(Str::contains(strtolower($log->user_agent), 'mac'))
                                            <i class="fab fa-apple text-secondary" title="Mac"></i>
                                        @elseif(Str::contains(strtolower($log->user_agent), 'linux'))
                                            <i class="fab fa-linux text-dark" title="Linux"></i>
                                        @elseif(Str::contains(strtolower($log->user_agent), 'android'))
                                            <i class="fab fa-android text-success" title="Android"></i>
                                        @elseif(Str::contains(strtolower($log->user_agent), 'iphone') || Str::contains(strtolower($log->user_agent), 'ipad'))
                                            <i class="fab fa-apple text-dark" title="iOS"></i>
                                        @else
                                            <i class="fas fa-desktop text-muted"></i>
                                        @endif
                                        
                                        @if(Str::contains(strtolower($log->user_agent), 'chrome'))
                                            <i class="fab fa-chrome text-warning ml-1" title="Chrome"></i>
                                        @elseif(Str::contains(strtolower($log->user_agent), 'firefox'))
                                            <i class="fab fa-firefox text-orange ml-1" title="Firefox"></i>
                                        @elseif(Str::contains(strtolower($log->user_agent), 'safari') && !Str::contains(strtolower($log->user_agent), 'chrome'))
                                            <i class="fab fa-safari text-primary ml-1" title="Safari"></i>
                                        @elseif(Str::contains(strtolower($log->user_agent), 'edge'))
                                            <i class="fab fa-edge text-info ml-1" title="Edge"></i>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="text-center py-5">
                                    <div class="d-flex flex-column align-items-center">
                                        <i class="fas fa-shield-alt fa-3x text-muted mb-3 opacity-50"></i>
                                        <h5 class="font-weight-light text-muted">Belum ada aktivitas terekam.</h5>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            
            <div class="card-footer bg-light border-top pt-3 pb-0 d-flex justify-content-center">
                {{ $logs->links('pagination::bootstrap-4') }}
            </div>
        </div>

    </section>

<style>
    .shadow-xs { box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05); }
    .table td { vertical-align: middle; }
    .last-border-0:last-child { border-bottom: 0 !important; margin-bottom: 0 !important; padding-bottom: 0 !important; }
</style>
@endsection
