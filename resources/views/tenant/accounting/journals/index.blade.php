@extends('layouts.app')

@section('content')
<div class="content-header pt-3 pb-2">
    <div class="container-fluid">
        <div class="row align-items-center">
            <div class="col-sm-6">
                <h1 class="m-0 text-dark font-weight-bold" style="font-size: 1.6rem;">Jurnal Umum</h1>
                <p class="text-sm text-muted mb-0">Riwayat dan pencatatan transaksi akuntansi dapur.</p>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right bg-transparent p-0 m-0 text-sm">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard', tenant('id')) }}"><i class="fas fa-home"></i> Dasbor</a></li>
                    <li class="breadcrumb-item active">Jurnal Umum</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<section class="content">
    <div class="container-fluid">
        <!-- Summary Widgets -->
        <div class="row mb-3">
            <div class="col-md-4">
                <div class="info-box shadow-sm border-0">
                    <span class="info-box-icon bg-info opacity-75"><i class="fas fa-history"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text text-xs uppercase font-weight-bold text-muted">Total Transaksi</span>
                        <span class="info-box-number h5 mb-0">{{ number_format($journals->count()) }} <small class="text-muted font-weight-normal">Record</small></span>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="info-box shadow-sm border-0">
                    <span class="info-box-icon bg-success opacity-75"><i class="fas fa-arrow-down"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text text-xs uppercase font-weight-bold text-muted">Total Debit</span>
                        <span class="info-box-number h5 mb-0 text-success">Rp {{ number_format($journals->sum('total_amount'), 0, ',', '.') }}</span>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="info-box shadow-sm border-0">
                    <span class="info-box-icon bg-primary opacity-75"><i class="fas fa-arrow-up"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text text-xs uppercase font-weight-bold text-muted">Total Kredit</span>
                        <span class="info-box-number h5 mb-0 text-primary">Rp {{ number_format($journals->sum('total_amount'), 0, ',', '.') }}</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="card card-outline card-info shadow-sm border-0">
            <div class="card-header bg-white py-3">
                <div class="row align-items-center">
                    <div class="col">
                        <h5 class="card-title font-weight-bold text-dark mb-0"><i class="fas fa-list-ul mr-2 text-info"></i> Daftar Jurnal Transaksi</h5>
                    </div>
                    <div class="col text-right">
                        <button type="button" class="btn btn-info btn-sm rounded-pill px-4 shadow-sm font-weight-bold" data-toggle="modal" data-target="#journalModal">
                            <i class="fas fa-plus mr-1"></i> Entri Jurnal Manual
                        </button>
                    </div>
                </div>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0 text-sm">
                        <thead class="bg-gray-100">
                            <tr class="text-muted text-xs text-uppercase">
                                <th class="border-top-0 pl-4 py-3">Tanggal</th>
                                <th class="border-top-0 py-3">No. Referensi</th>
                                <th class="border-top-0 py-3">Keterangan</th>
                                <th class="border-top-0 py-3">Modul</th>
                                <th class="border-top-0 py-3 text-right text-dark">Total Debet/Kredit</th>
                                <th class="border-top-0 py-3 text-center pr-4">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($journals as $journal)
                                <tr>
                                    <td class="pl-4 align-middle">{{ \Carbon\Carbon::parse($journal->date)->format('d F Y') }}</td>
                                    <td class="align-middle">
                                        <span class="badge badge-light border px-2 py-1 font-weight-bold text-primary">{{ $journal->reference_number }}</span>
                                    </td>
                                    <td class="align-middle font-weight-normal text-dark">{{ $journal->description }}</td>
                                    <td class="align-middle">
                                        @if($journal->source_module == 'manual')
                                            <span class="badge badge-info text-xs px-2 py-1 rounded-pill">Manual</span>
                                        @elseif($journal->source_module == 'inventory_in')
                                            <span class="badge badge-success text-xs px-2 py-1 rounded-pill">Stok Masuk</span>
                                        @elseif($journal->source_module == 'inventory_out')
                                            <span class="badge badge-warning text-xs px-2 py-1 rounded-pill">Stok Keluar</span>
                                        @else
                                            <span class="badge badge-secondary text-xs px-2 py-1 rounded-pill">{{ ucfirst(str_replace('_', ' ', $journal->source_module)) }}</span>
                                        @endif
                                    </td>
                                    <td class="text-right align-middle font-weight-bold text-dark">Rp {{ number_format($journal->total_amount, 0, ',', '.') }}</td>
                                    <td class="text-center align-middle pr-4">
                                        <button type="button" class="btn btn-outline-info btn-xs rounded-pill px-3 shadow-none view-journal-btn" 
                                            data-id="{{ $journal->id }}" 
                                            data-ref="{{ $journal->reference_number }}"
                                            data-date="{{ \Carbon\Carbon::parse($journal->date)->format('d F Y') }}"
                                            data-desc="{{ $journal->description }}"
                                            data-details='{{ $journal->details->map(fn($d) => [
                                                "account" => $d->account->code . " - " . $d->account->name,
                                                "desc" => $d->description,
                                                "debit" => number_format($d->debit, 0, ",", "."),
                                                "credit" => number_format($d->credit, 0, ",", ".")
                                            ])->toJson() }}'>
                                            <i class="fas fa-eye mr-1"></i> Rincian
                                        </button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center py-5">
                                        <div class="py-4">
                                            <i class="fas fa-search fa-3x text-light mb-3"></i>
                                            <h6 class="text-muted font-weight-normal">Belum ada transaksi jurnal yang tercatat.</h6>
                                        </div>
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

<!-- Journal Entry Modal (Premium Design) -->
<div class="modal fade" id="journalModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered" role="document">
        <form action="{{ route('accounting.journals.store', tenant('id')) }}" method="POST" class="modal-content border-0 shadow-lg" style="border-radius: 12px;">
            @csrf
            <div class="modal-header bg-info text-white" style="border-top-left-radius: 12px; border-top-right-radius: 12px;">
                <h5 class="modal-title font-weight-bold"><i class="fas fa-plus-circle mr-2"></i> Buat Entri Jurnal Manual</h5>
                <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body p-4 bg-light">
                <div class="row mb-4">
                    <div class="col-md-4">
                        <div class="form-group mb-0">
                            <label class="text-xs font-weight-bold text-uppercase text-muted">Tanggal Transaksi</label>
                            <input type="date" name="date" class="form-control border-0 shadow-sm" style="border-radius: 8px;" required value="{{ date('Y-m-d') }}">
                        </div>
                    </div>
                    <div class="col-md-8">
                        <div class="form-group mb-0">
                            <label class="text-xs font-weight-bold text-uppercase text-muted">Keterangan Utama</label>
                            <input type="text" name="description" class="form-control border-0 shadow-sm" style="border-radius: 8px;" required placeholder="Contoh: Penyesuaian Kas / Belanja Lain-lain">
                        </div>
                    </div>
                </div>

                <div class="card shadow-none border mb-0" style="border-radius: 10px;">
                    <div class="card-body p-0">
                        <table class="table table-flush mb-0" id="journal-table">
                            <thead class="bg-gray-100">
                                <tr class="text-xs text-uppercase text-muted">
                                    <th class="border-0 px-3" style="width: 40%;">Akun</th>
                                    <th class="border-0 px-3">Keterangan Baris</th>
                                    <th class="border-0 px-3" style="width: 180px;">Debit (Rp)</th>
                                    <th class="border-0 px-3" style="width: 180px;">Kredit (Rp)</th>
                                    <th class="border-0 text-center" style="width: 50px;"></th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- Dinamis Baris Jurnal -->
                                <tr class="journal-row">
                                    <td class="px-2 py-3 border-0">
                                        <select name="details[0][account_id]" class="form-control border-0 bg-white" required>
                                            <option value="">-- Pilih Akun --</option>
                                            @foreach($accounts as $acc)
                                                <option value="{{ $acc->id }}">{{ $acc->code }} - {{ $acc->name }}</option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td class="px-2 py-3 border-0">
                                        <input type="text" name="details[0][description]" class="form-control border-0 bg-white" placeholder="Opsional...">
                                    </td>
                                    <td class="px-2 py-3 border-0">
                                        <input type="number" name="details[0][debit]" class="form-control border-0 bg-white text-right debit-input" value="0" min="0">
                                    </td>
                                    <td class="px-2 py-3 border-0">
                                        <input type="number" name="details[0][credit]" class="form-control border-0 bg-white text-right credit-input" value="0" min="0">
                                    </td>
                                    <td class="text-center border-0 py-3"></td>
                                </tr>
                                <tr class="journal-row">
                                    <td class="px-2 py-3 border-0">
                                        <select name="details[1][account_id]" class="form-control border-0 bg-white" required>
                                            <option value="">-- Pilih Akun --</option>
                                            @foreach(App\Models\Tenant\Account::orderBy('code')->get() as $acc)
                                                <option value="{{ $acc->id }}">{{ $acc->code }} - {{ $acc->name }}</option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td class="px-2 py-3 border-0">
                                        <input type="text" name="details[1][description]" class="form-control border-0 bg-white" placeholder="Opsional...">
                                    </td>
                                    <td class="px-2 py-3 border-0">
                                        <input type="number" name="details[1][debit]" class="form-control border-0 bg-white text-right debit-input" value="0" min="0">
                                    </td>
                                    <td class="px-2 py-3 border-0">
                                        <input type="number" name="details[1][credit]" class="form-control border-0 bg-white text-right credit-input" value="0" min="0">
                                    </td>
                                    <td class="text-center border-0 py-3"></td>
                                </tr>
                            </tbody>
                            <tfoot class="bg-light">
                                <tr class="font-weight-bold">
                                    <td colspan="2" class="text-right py-3 pr-4">TOTAL :</td>
                                    <td class="text-right py-3 text-info" id="total-debit">Rp 0</td>
                                    <td class="text-right py-3 text-primary" id="total-credit">Rp 0</td>
                                    <td class="py-3"></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
                <button type="button" class="btn btn-link text-info btn-sm pl-0 mt-2" id="add-row">
                    <i class="fas fa-plus-circle mr-1"></i> Tambah Baris Akun
                </button>
            </div>
            <div class="modal-footer bg-white border-0" style="border-bottom-left-radius: 12px; border-bottom-right-radius: 12px;">
                <div class="mr-auto">
                    <span id="balance-badge" class="badge badge-danger px-3 py-2 rounded-pill font-weight-bold" style="display: none;">TIDAK SEIMBANG</span>
                    <span id="balanced-badge" class="badge badge-success px-3 py-2 rounded-pill font-weight-bold" style="display: none;">SEIMBANG ✔</span>
                </div>
                <button type="button" class="btn btn-secondary rounded-pill px-4" data-dismiss="modal">Batal</button>
                <button type="submit" id="btn-save-journal" class="btn btn-info rounded-pill px-5 shadow-sm font-weight-bold" disabled>SIMPAN JURNAL</button>
            </div>
        </form>
    </div>
</div>
<!-- Detail Journal Modal -->
<div class="modal fade" id="viewJournalModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 12px;">
            <div class="modal-header bg-dark text-white" style="border-top-left-radius: 12px; border-top-right-radius: 12px;">
                <h5 class="modal-title font-weight-bold"><i class="fas fa-file-alt mr-2"></i> Rincian Jurnal: <span id="v-ref" class="text-info"></span></h5>
                <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body p-4 bg-light">
                <div class="row mb-3 pb-3 border-bottom">
                    <div class="col-md-6">
                        <small class="text-uppercase text-muted font-weight-bold d-block">Tanggal</small>
                        <span id="v-date" class="font-weight-bold"></span>
                    </div>
                    <div class="col-md-6 text-right">
                        <small class="text-uppercase text-muted font-weight-bold d-block">Keterangan Utama</small>
                        <span id="v-desc" class="font-weight-bold"></span>
                    </div>
                </div>

                <div class="table-responsive bg-white rounded shadow-sm">
                    <table class="table mb-0">
                        <thead class="bg-gray-100">
                            <tr class="text-xs text-uppercase text-muted">
                                <th class="border-0 px-3">Akun</th>
                                <th class="border-0 px-3">Keterangan</th>
                                <th class="border-0 px-3 text-right">Debit (Rp)</th>
                                <th class="border-0 px-3 text-right">Kredit (Rp)</th>
                            </tr>
                        </thead>
                        <tbody id="v-details-body">
                            <!-- Filled by JS -->
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer border-0">
                <button type="button" class="btn btn-secondary rounded-pill px-4" data-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>

@endsection

@push('js')
<script>
    $(document).ready(function() {
        
        // Handle detail view
        $('.view-journal-btn').click(function() {
            const data = $(this).data();
            $('#v-ref').text(data.ref);
            $('#v-date').text(data.date);
            $('#v-desc').text(data.desc);

            let html = '';
            const details = data.details;
            details.forEach(d => {
                html += `
                    <tr class="text-sm">
                        <td class="px-3 py-3 border-top-0">${d.account}</td>
                        <td class="px-3 py-3 border-top-0 text-muted">${d.desc}</td>
                        <td class="px-3 py-3 border-top-0 text-right font-weight-bold text-info">${d.debit == '0' ? '-' : 'Rp ' + d.debit}</td>
                        <td class="px-3 py-3 border-top-0 text-right font-weight-bold text-primary">${d.credit == '0' ? '-' : 'Rp ' + d.credit}</td>
                    </tr>
                `;
            });
            $('#v-details-body').html(html);
            $('#viewJournalModal').modal('show');
        });

        let rowCount = 2;

        $('#add-row').click(function() {
            let options = $('#journal-table select').first().html();
            let newRow = `
                <tr class="journal-row">
                    <td class="px-2 py-3 border-0">
                        <select name="details[${rowCount}][account_id]" class="form-control border-0 bg-white" required>
                            ${options}
                        </select>
                    </td>
                    <td class="px-2 py-3 border-0">
                        <input type="text" name="details[${rowCount}][description]" class="form-control border-0 bg-white" placeholder="Opsional...">
                    </td>
                    <td class="px-2 py-3 border-0">
                        <input type="number" name="details[${rowCount}][debit]" class="form-control border-0 bg-white text-right debit-input" value="0" min="0">
                    </td>
                    <td class="px-2 py-3 border-0">
                        <input type="number" name="details[${rowCount}][credit]" class="form-control border-0 bg-white text-right credit-input" value="0" min="0">
                    </td>
                    <td class="text-center border-0 py-3">
                        <button type="button" class="btn btn-xs btn-outline-danger remove-row"><i class="fas fa-times"></i></button>
                    </td>
                </tr>
            `;
            $('#journal-table tbody').append(newRow);
            rowCount++;
        });

        $(document).on('click', '.remove-row', function() {
            $(this).closest('tr').remove();
            calculateTotals();
        });

        $(document).on('input', '.debit-input, .credit-input', function() {
            calculateTotals();
        });

        function calculateTotals() {
            let totalDebit = 0;
            let totalCredit = 0;

            $('.debit-input').each(function() {
                totalDebit += parseFloat($(this).val()) || 0;
            });

            $('.credit-input').each(function() {
                totalCredit += parseFloat($(this).val()) || 0;
            });

            $('#total-debit').text('Rp ' + new Intl.NumberFormat('id-ID').format(totalDebit));
            $('#total-credit').text('Rp ' + new Intl.NumberFormat('id-ID').format(totalCredit));

            if (totalDebit === totalCredit && totalDebit > 0) {
                $('#balanced-badge').show();
                $('#balance-badge').hide();
                $('#btn-save-journal').prop('disabled', false);
            } else {
                $('#balanced-badge').hide();
                if (totalDebit > 0 || totalCredit > 0) {
                    $('#balance-badge').show();
                } else {
                    $('#balance-badge').hide();
                }
                $('#btn-save-journal').prop('disabled', true);
            }
        }
    });
</script>
@endpush
