@extends('layouts.app')

@section('content')
<div class="content-header pt-3 pb-2">
    <div class="container-fluid">
        <div class="row align-items-center">
            <div class="col-sm-6">
                <h1 class="m-0 text-dark font-weight-bold" style="font-size: 1.6rem;">Data Supplier</h1>
                <p class="text-sm text-muted mb-0">Kelola daftar pemasok bahan baku dapur Anda.</p>
            </div>
            <div class="col-sm-6 text-right">
                <button type="button" class="btn btn-primary btn-sm rounded-pill px-3 shadow-sm" data-toggle="modal" data-target="#addSupplierModal">
                    <i class="fas fa-plus mr-1 text-xs"></i> Tambah Supplier
                </button>
            </div>
        </div>
    </div>
</div>

<section class="content">
    <div class="container-fluid">
        <div class="card card-outline card-primary shadow-sm border-0">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover table-striped mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th class="border-0 px-4" style="width: 50px;">#</th>
                                <th class="border-0">Nama Supplier</th>
                                <th class="border-0">Kontak Person</th>
                                <th class="border-0">No. Telepon / Email</th>
                                <th class="border-0">Alamat</th>
                                <th class="border-0 text-center">Status</th>
                                <th class="border-0 text-right px-4">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($suppliers as $supplier)
                            <tr>
                                <td class="px-4 text-muted">{{ $loop->iteration }}</td>
                                <td>
                                    <div class="font-weight-bold text-dark">{{ $supplier->name }}</div>
                                </td>
                                <td>{{ $supplier->contact_person ?? '-' }}</td>
                                <td>
                                    <div><i class="fas fa-phone text-xs mr-1 text-muted"></i> {{ $supplier->phone ?? '-' }}</div>
                                    <div class="text-xs text-muted"><i class="fas fa-envelope mr-1"></i> {{ $supplier->email ?? '-' }}</div>
                                </td>
                                <td class="text-sm">{{ Str::limit($supplier->address, 50) ?? '-' }}</td>
                                <td class="text-center">
                                    <span class="badge {{ $supplier->is_active ? 'badge-success' : 'badge-danger' }} px-2 py-1" style="font-size: 0.7rem;">
                                        {{ $supplier->is_active ? 'AKTIF' : 'NON-AKTIF' }}
                                    </span>
                                </td>
                                <td class="text-right px-4">
                                    <button type="button" class="btn btn-info btn-xs rounded-pill px-2 shadow-sm" 
                                            data-toggle="modal" data-target="#editSupplierModal{{ $supplier->id }}">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <form action="{{ route('procurement.suppliers.destroy', [tenant('id'), $supplier->id]) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus supplier ini?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-xs rounded-pill px-2 shadow-sm">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>

                            <!-- Edit Modal -->
                            <div class="modal fade" id="editSupplierModal{{ $supplier->id }}" tabindex="-1" role="dialog" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered" role="document">
                                    <form action="{{ route('procurement.suppliers.update', [tenant('id'), $supplier->id]) }}" method="POST" class="modal-content border-0 shadow-lg" style="border-radius: 15px;">
                                        @csrf @method('PUT')
                                        <div class="modal-header bg-info text-white" style="border-radius: 15px 15px 0 0;">
                                            <h5 class="modal-title font-weight-bold"><i class="fas fa-edit mr-2"></i> Edit Supplier</h5>
                                            <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body p-4">
                                            <div class="form-group">
                                                <label class="text-xs font-weight-bold text-uppercase text-muted">Nama Supplier</label>
                                                <input type="text" name="name" class="form-control" value="{{ $supplier->name }}" required>
                                            </div>
                                            <div class="form-group">
                                                <label class="text-xs font-weight-bold text-uppercase text-muted">Kontak Person</label>
                                                <input type="text" name="contact_person" class="form-control" value="{{ $supplier->contact_person }}">
                                            </div>
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label class="text-xs font-weight-bold text-uppercase text-muted">Telepon</label>
                                                        <input type="text" name="phone" class="form-control" value="{{ $supplier->phone }}">
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label class="text-xs font-weight-bold text-uppercase text-muted">Email</label>
                                                        <input type="email" name="email" class="form-control" value="{{ $supplier->email }}">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="text-xs font-weight-bold text-uppercase text-muted">Alamat</label>
                                                <textarea name="address" class="form-control" rows="2">{{ $supplier->address }}</textarea>
                                            </div>
                                            <div class="form-group">
                                                <label class="text-xs font-weight-bold text-uppercase text-muted">Status</label>
                                                <select name="is_active" class="form-control">
                                                    <option value="1" {{ $supplier->is_active ? 'selected' : '' }}>Aktif</option>
                                                    <option value="0" {{ !$supplier->is_active ? 'selected' : '' }}>Non-Aktif</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="modal-footer bg-light border-0" style="border-radius: 0 0 15px 15px;">
                                            <button type="button" class="btn btn-secondary rounded-pill px-4" data-dismiss="modal">Batal</button>
                                            <button type="submit" class="btn btn-info rounded-pill px-4 shadow-sm text-white font-weight-bold">Simpan Perubahan</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                            @empty
                            <tr>
                                <td colspan="7" class="text-center py-5 text-muted">
                                    <i class="fas fa-users-slash fa-3x mb-3 text-light"></i>
                                    <p>Belum ada data supplier.</p>
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

<!-- Add Modal -->
<div class="modal fade" id="addSupplierModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <form action="{{ route('procurement.suppliers.store', tenant('id')) }}" method="POST" class="modal-content border-0 shadow-lg" style="border-radius: 15px;">
            @csrf
            <div class="modal-header bg-primary text-white" style="border-radius: 15px 15px 0 0;">
                <h5 class="modal-title font-weight-bold"><i class="fas fa-plus-circle mr-2"></i> Tambah Supplier Baru</h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body p-4">
                <div class="form-group">
                    <label class="text-xs font-weight-bold text-uppercase text-muted">Nama Supplier <span class="text-danger">*</span></label>
                    <input type="text" name="name" class="form-control border-0 shadow-sm" required placeholder="Nama Perusahaan / Toko" style="border-radius: 8px;">
                </div>
                <div class="form-group">
                    <label class="text-xs font-weight-bold text-uppercase text-muted">Kontak Person</label>
                    <input type="text" name="contact_person" class="form-control border-0 shadow-sm" placeholder="Nama PIC" style="border-radius: 8px;">
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="text-xs font-weight-bold text-uppercase text-muted">Telepon</label>
                            <input type="text" name="phone" class="form-control border-0 shadow-sm" placeholder="0812..." style="border-radius: 8px;">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="text-xs font-weight-bold text-uppercase text-muted">Email</label>
                            <input type="email" name="email" class="form-control border-0 shadow-sm" placeholder="email@supplier.com" style="border-radius: 8px;">
                        </div>
                    </div>
                </div>
                <div class="form-group mb-0">
                    <label class="text-xs font-weight-bold text-uppercase text-muted">Alamat</label>
                    <textarea name="address" class="form-control border-0 shadow-sm" rows="2" placeholder="Alamat lengkap..." style="border-radius: 8px;"></textarea>
                </div>
            </div>
            <div class="modal-footer bg-light border-0" style="border-radius: 0 0 15px 15px;">
                <button type="button" class="btn btn-secondary rounded-pill px-4" data-dismiss="modal">Batal</button>
                <button type="submit" class="btn btn-primary rounded-pill px-5 shadow-sm font-weight-bold">Simpan Supplier</button>
            </div>
        </form>
    </div>
</div>
@endsection
