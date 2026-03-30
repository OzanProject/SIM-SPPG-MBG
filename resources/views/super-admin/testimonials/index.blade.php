@extends('layouts.app')

@section('title', 'Manajemen Testimoni')

@section('content')
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0 text-dark">Manajemen Testimoni</h1>
            </div>
            <div class="col-sm-6 text-right">
                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modal-add">
                    <i class="fas fa-plus mr-1"></i> Tambah Testimoni
                </button>
            </div>
        </div>
    </div>
</div>

<section class="content">
    <div class="container-fluid">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif

        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Daftar Testimoni Client</h3>
            </div>
            <div class="card-body p-0">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th style="width: 50px">#</th>
                            <th style="width: 60px">Foto</th>
                            <th>Nama Client</th>
                            <th>Sumber / Tenant</th>
                            <th style="width: 120px">Rating</th>
                            <th>Isi Testimoni</th>
                            <th style="width: 100px">Status</th>
                            <th style="width: 150px">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($testimonials as $testi)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>
                                    <img src="{{ $testi->image_url ? asset('storage/' . $testi->image_url) : 'https://ui-avatars.com/api/?name='.urlencode($testi->name) }}" 
                                         class="img-circle elevation-1" style="width: 40px; height: 40px; object-fit: cover;">
                                </td>
                                <td><span class="font-weight-bold">{{ $testi->name }}</span></td>
                                <td>
                                    @if($testi->source == 'tenant')
                                        <span class="badge badge-info"><i class="fas fa-home mr-1"></i> {{ strtoupper($testi->tenant_id) }}</span>
                                    @else
                                        <span class="badge badge-secondary"><i class="fas fa-user-shield mr-1"></i> Internal</span>
                                    @endif
                                </td>
                                <td>
                                    @for($i = 1; $i <= 5; $i++)
                                        <i class="fas fa-star {{ $i <= $testi->rating ? 'text-warning' : 'text-muted' }} text-xs"></i>
                                    @endfor
                                </td>
                                <td><small>{{ Str::limit($testi->content, 60) }}</small></td>
                                <td>
                                    @if($testi->is_active)
                                        <span class="badge badge-success">Aktif</span>
                                    @else
                                        <span class="badge badge-warning">Pending Approval</span>
                                    @endif
                                </td>
                                <td>
                                    <form action="{{ route('testimonials.update', $testi->id) }}" method="POST" style="display:inline;">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="btn btn-sm {{ $testi->is_active ? 'btn-outline-warning' : 'btn-success' }}" title="{{ $testi->is_active ? 'Nonaktifkan' : 'Approve / Aktifkan' }}">
                                            <i class="fas {{ $testi->is_active ? 'fa-pause' : 'fa-check' }}"></i>
                                        </button>
                                    </form>
                                    <form action="{{ route('testimonials.destroy', $testi->id) }}" method="POST" style="display:inline;" onsubmit="return confirm('Hapus testimoni ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger" title="Hapus">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-4 text-muted">Belum ada testimoni.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="card-footer clearfix">
                {{ $testimonials->links() }}
            </div>
        </div>
    </div>
</section>

<!-- Modal Add -->
<div class="modal fade" id="modal-add">
    <div class="modal-dialog">
        <form action="{{ route('testimonials.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Tambah Testimoni Baru</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label>Nama Client</label>
                        <input type="text" name="name" class="form-control" placeholder="Contoh: Budi Santoso" required>
                    </div>
                    <div class="form-group">
                        <label>Rating</label>
                        <select name="rating" class="form-control" required>
                            <option value="5">⭐⭐⭐⭐⭐ (5 Bintang)</option>
                            <option value="4">⭐⭐⭐⭐ (4 Bintang)</option>
                            <option value="3">⭐⭐⭐ (3 Bintang)</option>
                            <option value="2">⭐⭐ (2 Bintang)</option>
                            <option value="1">⭐ (1 Bintang)</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Konten Testimoni</label>
                        <textarea name="content" class="form-control" rows="4" placeholder="Tulis testimoni pelanggan di sini..." required></textarea>
                    </div>
                    <div class="form-group">
                        <label>Foto Profile (Opsional)</label>
                        <div class="input-group">
                            <div class="custom-file">
                                <input type="file" name="image" class="custom-file-input">
                                <label class="custom-file-label">Pilih file...</label>
                            </div>
                        </div>
                        <small class="text-muted">Rekomendasi ukuran 1:1 (Square)</small>
                    </div>
                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan Testimoni</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
