@extends('layouts.app')

@section('title', 'Kesan & Pesan')

@section('content')
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0 text-dark">Kesan & Pesan</h1>
                <p class="text-muted">Bagikan pengalaman sukses Anda menggunakan MBG Akunpro.</p>
            </div>
        </div>
    </div>
</div>

<section class="content">
    <div class="container-fluid">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                <h5><i class="icon fas fa-check"></i> Berhasil!</h5>
                {{ session('success') }}
            </div>
        @endif

        <div class="row">
            <!-- Form Submission -->
            <div class="col-md-5">
                <div class="card card-outline card-warning shadow-sm">
                    <div class="card-header border-0" style="background: linear-gradient(135deg, #f39c12, #e67e22); color: white;">
                        <h3 class="card-title font-weight-bold"><i class="fas fa-medal mr-2"></i> Bagikan Kisah Sukses Anda</h3>
                    </div>
                    <form action="{{ route('tenant.testimonials.store', tenant('id')) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="card-body">
                            <div class="form-group text-center mb-4">
                                <label class="d-block">Berikan Rating Anda</label>
                                <div class="rating-stars">
                                    <input type="radio" name="rating" value="5" id="5" checked><label for="5">☆</label>
                                    <input type="radio" name="rating" value="4" id="4"><label for="4">☆</label>
                                    <input type="radio" name="rating" value="3" id="3"><label for="3">☆</label>
                                    <input type="radio" name="rating" value="2" id="2"><label for="2">☆</label>
                                    <input type="radio" name="rating" value="1" id="1"><label for="1">☆</label>
                                </div>
                                <style>
                                    .rating-stars { display: flex; flex-direction: row-reverse; justify-content: center; }
                                    .rating-stars input { display: none; }
                                    .rating-stars label { font-size: 40px; color: #ddd; cursor: pointer; transition: color 0.2s; }
                                    .rating-stars label:hover, .rating-stars label:hover ~ label, .rating-stars input:checked ~ label { color: #f39c12; }
                                    .rating-stars label:hover:before, .rating-stars label:hover ~ label:before, .rating-stars input:checked ~ label:before { content: "\2605"; position: absolute; }
                                </style>
                            </div>

                            <div class="form-group">
                                <label>Apa yang Anda rasakan? (Minimal 10 karakter)</label>
                                <textarea name="content" class="form-control" rows="5" placeholder="Contoh: Sangat puas dengan fitur laporan keuangannya..." required minlength="10"></textarea>
                            </div>

                            <div class="form-group">
                                <label>Unggah Foto Anda (Opsional)</label>
                                <div class="custom-file">
                                    <input type="file" name="image" class="custom-file-input" id="testiImage">
                                    <label class="custom-file-label" for="testiImage">Pilih file...</label>
                                </div>
                                <small class="text-muted">Foto ini akan ditampilkan di Landing Page MBG Akunpro.</small>
                            </div>
                        </div>
                        <div class="card-footer text-right">
                            <button type="submit" class="btn btn-primary px-4 font-weight-bold">
                                <i class="fas fa-paper-plane mr-1"></i> Kirim Testimoni
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- History -->
            <div class="col-md-7">
                <div class="card">
                    <div class="card-header bg-light">
                        <h3 class="card-title">Riwayat Testimoni Anda</h3>
                    </div>
                    <div class="card-body p-0">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Tanggal</th>
                                    <th>Rating</th>
                                    <th>Pesan</th>
                                    <th>Status Approval</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($testimonials as $testi)
                                    <tr>
                                        <td>{{ $testi->created_at->format('d M Y') }}</td>
                                        <td>
                                            @for($i=1; $i<=5; $i++)
                                                <i class="fas fa-star {{ $i <= $testi->rating ? 'text-warning' : 'text-muted' }} text-xs"></i>
                                            @endfor
                                        </td>
                                        <td><small>{{ Str::limit($testi->content, 60) }}</small></td>
                                        <td>
                                            @if($testi->is_active)
                                                <span class="badge badge-success">Disetujui / Aktif</span>
                                            @else
                                                <span class="badge badge-warning">Menunggu Moderasi</span>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center py-5 text-muted">Belum ada testimoni yang dikirim.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                
                <div class="alert alert-info mt-3">
                    <h5><i class="icon fas fa-info"></i> Info Kurasi</h5>
                    Setiap testimoni yang Anda kirim akan ditinjau oleh tim MBG Akunpro sebelum ditampilkan secara publik di Landing Page. Kami menghargai setiap masukan Anda!
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
