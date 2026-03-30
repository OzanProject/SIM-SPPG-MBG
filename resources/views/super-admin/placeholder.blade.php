@extends('layouts.app')

@section('content')
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0 text-dark"><i class="fas {{ $icon }} mr-2"></i> {{ $title }}</h1>
            </div>
        </div>
    </div>
</div>

<section class="content">
    <div class="container-fluid">
        <div class="card card-outline card-primary">
            <div class="card-body text-center py-5">
                <i class="fas {{ $icon }} text-muted opacity-50 mb-4" style="font-size: 6rem;"></i>
                <h3 class="mb-3">Fitur Sedang Dalam Tahap Implementasi</h3>
                <p class="text-secondary lead">{{ $desc }}</p>
                <p class="text-muted text-sm mt-4">Pembaruan selanjutnya akan segera meluncurkan antarmuka penuh untuk modul ini. Terus pantau progres kami!</p>
                
                <a href="{{ url('/super-admin/dashboard') }}" class="btn btn-primary mt-3">
                    <i class="fas fa-arrow-left mr-2"></i> Kembali ke Dasbor
                </a>
            </div>
        </div>
    </div>
</section>
@endsection
