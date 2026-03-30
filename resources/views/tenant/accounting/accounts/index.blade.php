@extends('layouts.app')

@section('content')
<div class="content-header pt-3 pb-2">
    <div class="container-fluid">
        <div class="row align-items-center">
            <div class="col-sm-6">
                <h1 class="m-0 text-dark font-weight-bold" style="font-size: 1.6rem;">Bagan Akun (COA)</h1>
                <p class="text-sm text-muted mb-0">Kelola struktur akun keuangan untuk pelaporan dapur.</p>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right bg-transparent p-0 m-0 text-sm">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard', tenant('id')) }}"><i class="fas fa-home"></i> Dasbor</a></li>
                    <li class="breadcrumb-item active">Bagan Akun</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<section class="content">
    <div class="container-fluid">
        <div class="card card-outline card-info shadow-sm border-0">
            <div class="card-header bg-white py-3">
                <div class="row align-items-center">
                    <div class="col">
                        <h5 class="card-title font-weight-bold text-dark mb-0">Chart of Accounts</h5>
                    </div>
                    <div class="col text-right">
                        <button type="button" class="btn btn-info btn-sm rounded-pill px-3 shadow-sm" data-toggle="modal" data-target="#accountModal">
                            <i class="fas fa-plus mr-1 text-xs"></i> Tambah Akun
                        </button>
                    </div>
                </div>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th class="border-0 px-4" style="width: 120px;">Kode</th>
                                <th class="border-0">Nama Akun</th>
                                <th class="border-0">Tipe</th>
                                <th class="border-0 text-center">Saldo Normal</th>
                                <th class="border-0 text-center">Status</th>
                                <th class="border-0 px-4 text-right" style="width: 120px;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach(['asset' => 'AKTIVA (ASSETS)', 'liability' => 'KEWAJIBAN (LIABILITIES)', 'equity' => 'MODAL (EQUITY)', 'revenue' => 'PENDAPATAN (REVENUE)', 'expense' => 'BEBAN (EXPENSES)'] as $type => $label)
                                <tr class="bg-gray-light">
                                    <td colspan="6" class="px-4 py-2 font-weight-bold text-xs text-muted" style="background-color: #f4f6f9; letter-spacing: 1px;">{{ $label }}</td>
                                </tr>
                                @foreach($accounts->where('type', $type) as $account)
                                <tr>
                                    <td class="px-4 font-weight-bold text-info">{{ $account->code }}</td>
                                    <td class="font-weight-bold text-dark">{{ $account->name }}</td>
                                    <td><span class="badge badge-pill badge-light border text-xs">{{ strtoupper($account->type) }}</span></td>
                                    <td class="text-center font-weight-bold text-xs text-muted">{{ strtoupper($account->normal_balance) }}</td>
                                    <td class="text-center">
                                        <span class="badge {{ $account->is_active ? 'badge-success' : 'badge-secondary' }} badge-pill p-1 px-2" style="font-size: 0.65rem;">
                                            {{ $account->is_active ? 'AKTIF' : 'NON-AKTIF' }}
                                        </span>
                                    </td>
                                    <td class="px-4 text-right">
                                        <div class="btn-group">
                                            <button type="button" class="btn btn-sm btn-outline-info mr-1 edit-account" data-id="{{ $account->id }}" data-json="{{ json_encode($account) }}">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <form action="{{ route('accounting.accounts.destroy', [tenant('id'), $account->id]) }}" method="POST" class="d-inline">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('Hapus akun ini?')">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Account Modal (Premium Look) -->
<div class="modal fade" id="accountModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <form id="accountForm" action="{{ route('accounting.accounts.store', tenant('id')) }}" method="POST" class="modal-content border-0 shadow-lg" style="border-radius: 12px;">
            @csrf
            <div class="modal-header bg-info text-white" style="border-top-left-radius: 12px; border-top-right-radius: 12px;">
                <h5 class="modal-title font-weight-bold"><i class="fas fa-plus-circle mr-2"></i> Tambah Akun Baru</h5>
                <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body p-4">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group mb-3 text-sm font-weight-bold">
                            <label>Kode Akun</label>
                            <input type="text" name="code" class="form-control" required placeholder="1101">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group mb-3 text-sm font-weight-bold">
                            <label>Tipe Akun</label>
                            <select name="type" class="form-control" required>
                                <option value="asset">Aktiva (Asset)</option>
                                <option value="liability">Kewajiban (Liability)</option>
                                <option value="equity">Modal (Equity)</option>
                                <option value="revenue">Pendapatan (Revenue)</option>
                                <option value="expense">Beban (Expense)</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="form-group mb-3 text-sm font-weight-bold">
                    <label>Nama Akun</label>
                    <input type="text" name="name" class="form-control" required placeholder="Contoh: Kas Utama Dapur">
                </div>
                <div class="form-group mb-3 text-sm font-weight-bold">
                    <label>Saldo Normal</label>
                    <select name="normal_balance" class="form-control" required>
                        <option value="debit">DEBIT</option>
                        <option value="credit">KREDIT</option>
                    </select>
                </div>
                <div class="form-group mb-0 text-sm font-weight-bold">
                    <label>Keterangan</label>
                    <textarea name="description" class="form-control" rows="2" placeholder="Penjelasan singkat akun..."></textarea>
                </div>
            </div>
            <div class="modal-footer bg-light border-0" style="border-bottom-left-radius: 12px; border-bottom-right-radius: 12px;">
                <button type="button" class="btn btn-secondary rounded-pill px-4" data-dismiss="modal">Batal</button>
                <button type="submit" class="btn btn-info rounded-pill px-5 shadow-sm font-weight-bold">SIMPAN AKUN</button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('js')
<script>
    $(document).ready(function() {
        $('.edit-account').click(function() {
            const data = $(this).data('json');
            const url = "{{ route('accounting.accounts.update', [tenant('id'), ':id']) }}".replace(':id', data.id);
            
            $('#accountForm').attr('action', url);
            $('#accountForm').prepend('<input type="hidden" name="_method" value="PUT">');
            
            $('.modal-title').html('<i class="fas fa-edit mr-2"></i> Edit Akun: ' + data.name);
            $('input[name="code"]').val(data.code);
            $('input[name="name"]').val(data.name);
            $('select[name="type"]').val(data.type);
            $('select[name="normal_balance"]').val(data.normal_balance);
            $('textarea[name="description"]').val(data.description);
            
            $('#accountModal').modal('show');
        });

        $('#accountModal').on('hidden.bs.modal', function () {
            $('#accountForm').attr('action', "{{ route('accounting.accounts.store', tenant('id')) }}");
            $('#accountForm').find('input[name="_method"]').remove();
            $('.modal-title').html('<i class="fas fa-plus-circle mr-2"></i> Tambah Akun Baru');
            $('#accountForm')[0].reset();
        });
    });
</script>
@endpush
