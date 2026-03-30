@extends('layouts.app')

@push('css')
<style>
/* Adjust TinyMCE wrapper if needed */
.tox-tinymce {
    border-radius: 8px !important;
    border: 1px solid #dee2e6 !important;
}
</style>
@endpush

@section('content')
<div class="content-header pt-3 pb-2">
    <div class="container-fluid">
        <div class="row align-items-center mb-2">
            <div class="col-sm-6">
                <h1 class="m-0 text-dark font-weight-bold" style="font-size: 1.8rem;">
                    <i class="fas {{ isset($customPage) ? 'fa-edit' : 'fa-plus' }} text-primary mr-2"></i>
                    {{ isset($customPage) ? 'Edit Halaman: ' . $customPage->title : 'Tambah Halaman Kustom' }}
                </h1>
            </div>
            <div class="col-sm-6 text-right">
                <a href="{{ route('custom-pages.index') }}" class="btn btn-secondary shadow-sm">
                    <i class="fas fa-arrow-left mr-1"></i> Kembali
                </a>
            </div>
        </div>
    </div>
</div>

<section class="content">
    <div class="container-fluid">
        @if ($errors->any())
            <div class="alert alert-danger bg-danger text-white border-0 shadow-sm">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ isset($customPage) ? route('custom-pages.update', $customPage->id) : route('custom-pages.store') }}" method="POST">
            @csrf
            @if(isset($customPage))
                @method('PUT')
            @endif

            <div class="row">
                <div class="col-lg-8">
                    <div class="card shadow-sm border-0 mb-4">
                        <div class="card-header bg-white border-bottom">
                            <h3 class="card-title font-weight-bold"><i class="fas fa-pen-alt text-primary mr-2"></i>Konten Halaman</h3>
                        </div>
                        <div class="card-body">
                            <div class="form-group mb-4">
                                <label for="title" class="font-weight-bold">Judul Halaman <span class="text-danger">*</span></label>
                                <input type="text" class="form-control form-control-lg" id="title" name="title" 
                                    value="{{ old('title', $customPage->title ?? '') }}" 
                                    placeholder="Contoh: Ketentuan Layanan" required>
                            </div>

                            <div class="form-group">
                                <label for="content" class="font-weight-bold">Isi Halaman</label>
                                <textarea id="tinymce-editor" name="content">{{ old('content', $customPage->content ?? '') }}</textarea>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4">
                    <div class="card shadow-sm border-0 mb-4">
                        <div class="card-header bg-white border-bottom">
                            <h3 class="card-title font-weight-bold"><i class="fas fa-cogs text-info mr-2"></i>Pengaturan Halaman</h3>
                        </div>
                        <div class="card-body">
                            
                            <div class="form-group mb-4">
                                <label for="slug" class="font-weight-bold">URL Slug (Otomatis)</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text bg-light text-muted">/page/</span>
                                    </div>
                                    <input type="text" class="form-control bg-light" id="slug" name="slug" 
                                        value="{{ old('slug', $customPage->slug ?? '') }}" 
                                        placeholder="terisi otomatis" {{ isset($customPage) ? 'readonly' : '' }}>
                                </div>
                                <small class="text-muted text-xs">Biarkan kosong agar otomatis terisi dari Judul.</small>
                            </div>

                            <div class="form-group mb-4">
                                <div class="custom-control custom-switch custom-switch-off-danger custom-switch-on-success">
                                    <input type="checkbox" class="custom-control-input" id="is_active" name="is_active" 
                                           {{ old('is_active', $customPage->is_active ?? true) ? 'checked' : '' }}>
                                    <label class="custom-control-label" for="is_active">Status Aktif</label>
                                </div>
                                <small class="text-muted d-block mt-1">Halaman non-aktif akan menampilkan error 404.</small>
                            </div>

                            <div class="form-group mb-4">
                                <div class="custom-control custom-switch custom-switch-off-secondary custom-switch-on-success">
                                    <input type="checkbox" class="custom-control-input" id="show_in_footer" name="show_in_footer" 
                                           {{ old('show_in_footer', $customPage->show_in_footer ?? false) ? 'checked' : '' }}>
                                    <label class="custom-control-label" for="show_in_footer">Tampilkan otomatis di Footer</label>
                                </div>
                                <small class="text-muted d-block mt-1">Jika dicentang, otomatis muncul di block 'Legalitas' di bagian paling bawah website.</small>
                            </div>

                        </div>
                        <div class="card-footer bg-light">
                            <button type="submit" class="btn btn-primary btn-block btn-lg shadow-sm font-weight-bold">
                                <i class="fas fa-save mr-2"></i> Simpan Halaman
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</section>
@endsection

@push('js')
<!-- TinyMCE CDN with User API Key -->
<script src="https://cdn.tiny.cloud/1/nuww14eec90ohvwjq67sjn9fcqkn5mmyywap0caie6rk7xhs/tinymce/6/tinymce.min.js" referrerpolicy="origin"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        tinymce.init({
            selector: '#tinymce-editor',
            height: 500,
            menubar: false,
            plugins: [
                'advlist', 'autolink', 'lists', 'link', 'image', 'charmap', 'preview',
                'anchor', 'searchreplace', 'visualblocks', 'code', 'fullscreen',
                'insertdatetime', 'media', 'table', 'help', 'wordcount'
            ],
            toolbar: 'undo redo | blocks | ' +
            'bold italic textcolor | alignleft aligncenter ' +
            'alignright alignjustify | bullist numlist outdent indent | ' +
            'link image media table | removeformat | help',
            content_style: 'body { font-family:Helvetica,Arial,sans-serif; font-size:14px }',
            branding: false,
            promotion: false
        });

        // Slug auto generator (only on create)
        @if(!isset($customPage))
        const titleInput = document.getElementById('title');
        const slugInput = document.getElementById('slug');
        titleInput.addEventListener('input', function() {
            let slug = this.value.toLowerCase()
                .replace(/[^a-z0-9 -]/g, '') 
                .replace(/\s+/g, '-') 
                .replace(/-+/g, '-'); 
            slugInput.value = slug;
        });
        @endif
    });
</script>
@endpush
