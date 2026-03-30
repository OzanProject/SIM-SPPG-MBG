@extends('layouts.app')

@section('content')
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-12">
                <h1 class="m-0 text-dark">Daftarkan Cabang Dapur Baru</h1>
            </div>
        </div>
    </div>
</div>

<section class="content">
    <div class="container-fluid">
        <div class="card card-primary">
            <div class="card-header">
                <h3 class="card-title">Formulir Pendaftaran Subdomain</h3>
            </div>
            <form action="{{ url('/super-admin/tenants') }}" method="POST">
                @csrf
                <div class="card-body">
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <div class="form-group mb-3">
                        <label for="id">Nama Cabang / ID Tenant (Tanpa Spasi)</label>
                        <input type="text" class="form-control" name="id" id="id" placeholder="contoh: dapur-bandung" value="{{ old('id') }}" required>
                        <small class="form-text text-muted">ID ini akan menjadi URL akses cabang Anda (misal: /dapur-bandung/login). Gunakan huruf kecil, angka, dan strip (-).</small>
                    </div>
                </div>

                <div class="card-footer">
                    <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Daftarkan Cabang</button>
                    <a href="{{ url('/super-admin/tenants') }}" class="btn btn-default ml-2">Batal</a>
                </div>
            </form>
        </div>
    </div>
</section>
@endsection
