@extends('layouts.app')

@section('content')
<div class="content-header pt-4 pb-2">
    <div class="container-fluid">
        <div class="row align-items-center mb-2">
            <div class="col-sm-6">
                <h4 class="m-0 text-dark font-weight-bold" style="font-size: 1.5rem;">
                    <i class="fas fa-edit text-warning mr-2"></i> Perbarui Rencana Menu
                </h4>
                <p class="text-muted mb-0 small text-uppercase" style="letter-spacing: 0.5px;">Ubah rencana atau unggah dokumentasi untuk distribusi {{ $menu->target_date->format('d M Y') }}</p>
            </div>
            <div class="col-sm-6 text-right">
                <a href="{{ route('tenant.circle-menus.index', tenant('id')) }}" class="btn btn-default shadow-sm font-weight-bold">
                    <i class="fas fa-arrow-left mr-1"></i> Kembali ke Daftar
                </a>
            </div>
        </div>
    </div>
</div>

<section class="content mb-5">
    <div class="container-fluid">
        <form action="{{ route('tenant.circle-menus.update', [tenant('id'), $menu->id]) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="row">
                <div class="col-lg-8">
                    <div class="card card-outline card-warning shadow-sm rounded-lg" style="border-top-width: 3px;">
                        <div class="card-header bg-white border-bottom-0 pt-4 px-4 pb-0">
                            <h5 class="card-title font-weight-bold text-dark">
                                <i class="fas fa-info-circle text-warning mr-2"></i> Edit Detail Rencana
                            </h5>
                        </div>
                        <div class="card-body p-4">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="font-weight-bold text-dark mb-1">📅 Tanggal Distribusi <span class="text-danger">*</span></label>
                                    <input type="date" name="target_date" class="form-control form-control-lg @error('target_date') is-invalid @enderror" value="{{ old('target_date', $menu->target_date->format('Y-m-d')) }}" required>
                                    @error('target_date')<span class="error invalid-feedback">{{ $message }}</span>@enderror
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="font-weight-bold text-dark mb-1">🏫 Nama Lokasi / Sekolah <span class="text-danger">*</span></label>
                                    <input type="text" name="location_name" class="form-control form-control-lg @error('location_name') is-invalid @enderror" value="{{ old('location_name', $menu->location_name) }}" required>
                                    @error('location_name')<span class="error invalid-feedback">{{ $message }}</span>@enderror
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="font-weight-bold text-dark mb-1">🔢 Jumlah Target Porsi <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <input type="number" name="total_portions" class="form-control form-control-lg @error('total_portions') is-invalid @enderror" value="{{ old('total_portions', $menu->total_portions) }}" min="1" required>
                                        <div class="input-group-append">
                                            <span class="input-group-text font-weight-bold bg-light">PORSI</span>
                                        </div>
                                        @error('total_portions')<span class="error invalid-feedback">{{ $message }}</span>@enderror
                                    </div>
                                </div>
                            </div>

                            <hr class="my-4" style="border-top: 1px dashed rgba(0,0,0,.1);">

                            <div class="form-group mb-0">
                                <label class="font-weight-bold text-dark mb-2">🥗 Daftar Menu Makanan <span class="text-danger">*</span></label>
                                @php $menu_items_str = implode("\n", $menu->menu_items); @endphp
                                <textarea name="menu_items" class="form-control @error('menu_items') is-invalid @enderror" rows="6" required>{{ old('menu_items', $menu_items_str) }}</textarea>
                                @error('menu_items')<span class="error invalid-feedback">{{ $message }}</span>@enderror
                            </div>
                        </div>
                    </div>

                    {{-- CARD DOKUMENTASI --}}
                    <div class="card card-outline card-success shadow-sm rounded-lg" style="border-top-width: 3px;">
                        <div class="card-header bg-white border-bottom-0 pt-4 px-4 pb-0">
                            <h5 class="card-title font-weight-bold text-dark">
                                <i class="fas fa-camera text-success mr-2"></i> Bukti Dokumentasi Distribusi
                            </h5>
                        </div>
                        <div class="card-body p-4">
                            @if($menu->documentation_photo)
                                <div class="mb-4 text-center">
                                    <img src="{{ global_asset('storage/' . $menu->documentation_photo) }}" alt="Bukti Dokumentasi" class="img-fluid rounded-lg shadow-sm border" style="max-height: 250px;">
                                    <div class="mt-2 text-muted small"><i class="fas fa-check-circle text-success font-weight-bold mr-1"></i> Dokumentasi Terunggah</div>
                                </div>
                            @endif

                            <div class="form-group mb-0">
                                <label class="font-weight-bold text-dark mb-1">{{ $menu->documentation_photo ? 'Ganti' : 'Unggah' }} Foto Bukti <small class="text-muted">(Max 2MB)</small></label>
                                <div class="custom-file">
                                    <input type="file" name="documentation_photo" class="custom-file-input @error('documentation_photo') is-invalid @enderror" id="customFile">
                                    <label class="custom-file-label" for="customFile">{{ $menu->documentation_photo ? 'Pilih foto baru...' : 'Pilih foto dokumentasi...' }}</label>
                                    @error('documentation_photo')<span class="error invalid-feedback">{{ $message }}</span>@enderror
                                </div>
                                <p class="text-muted small mt-2">
                                    <i class="fas fa-info-circle text-info mr-1"></i> Foto dokumentasi biasanya berupa hidangan yang sudah sampai di sekolah atau bukti penerimaan oleh pihak sekolah.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4">
                    <div class="card card-outline card-warning shadow-sm rounded-lg" style="border-top-width: 3px;">
                        <div class="card-header bg-white border-bottom-0 pt-4 px-4 pb-0">
                            <h5 class="card-title font-weight-bold text-dark">
                                <i class="fas fa-check-double text-warning mr-2"></i> Update Status
                            </h5>
                        </div>
                        <div class="card-body p-4">
                            <div class="form-group mb-4">
                                <label class="font-weight-bold text-dark mb-1">Status Pekerjaan</label>
                                <select name="status" class="form-control font-weight-bold">
                                    <option value="draft" {{ $menu->status == 'draft' ? 'selected' : '' }}>Konsep (Draft)</option>
                                    <option value="processing" {{ $menu->status == 'processing' ? 'selected' : '' }}>Sedang Proses Distribusi</option>
                                    <option value="completed" {{ $menu->status == 'completed' ? 'selected' : '' }}>Selesai & Didistribusikan</option>
                                </select>
                            </div>

                            <button type="submit" class="btn btn-warning btn-lg btn-block shadow-sm font-weight-bold mb-2 py-3">
                                <i class="fas fa-save mr-2"></i> Simpan Perubahan
                            </button>
                            <a href="{{ route('tenant.circle-menus.index', tenant('id')) }}" class="btn btn-default btn-block py-2 font-weight-bold text-muted">Batal</a>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</section>
@endsection
