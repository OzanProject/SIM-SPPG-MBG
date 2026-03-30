@extends('layouts.app')

@section('content')
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0 font-weight-bold"><i class="fas fa-question-circle mr-2 text-success"></i> Data FAQ</h1>
            </div>
            <div class="col-sm-6 text-right">
                <a href="{{ route('support.faq.create') }}" class="btn btn-success shadow-sm"><i class="fas fa-plus mr-1"></i> Tambah FAQ</a>
            </div>
        </div>
    </div>
</div>

<section class="content">
    <div class="container-fluid">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible"><button type="button" class="close" data-dismiss="alert">&times;</button>{{ session('success') }}</div>
        @endif

        <div class="card shadow-sm border-0">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="bg-light text-sm">
                            <tr>
                                <th class="pl-4" width="50">#</th>
                                <th>Pertanyaan</th>
                                <th>Kategori</th>
                                <th width="100">Prioritas</th>
                                <th width="100">Status</th>
                                <th class="text-center" width="150">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($faqs as $index => $faq)
                                <tr>
                                    <td class="pl-4">{{ $faqs->firstItem() + $index }}</td>
                                    <td>
                                        <div class="font-weight-bold">{{ $faq->question }}</div>
                                        <small class="text-muted d-block mt-1 text-truncate" style="max-width: 400px;">{{ $faq->answer }}</small>
                                    </td>
                                    <td><span class="badge badge-info">{{ $faq->category }}</span></td>
                                    <td class="text-center font-weight-bold">{{ $faq->order_priority }}</td>
                                    <td class="text-center">
                                        @if($faq->is_active)
                                            <span class="badge badge-success">Aktif</span>
                                        @else
                                            <span class="badge badge-danger">Non-aktif</span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        <a href="{{ route('support.faq.edit', $faq) }}" class="btn btn-xs btn-primary"><i class="fas fa-edit"></i></a>
                                        <form action="{{ route('support.faq.destroy', $faq) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus FAQ ini?')">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="btn btn-xs btn-danger"><i class="fas fa-trash"></i></button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center py-5 text-muted">Belum ada data FAQ.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="card-footer bg-white">{{ $faqs->links() }}</div>
        </div>
    </div>
</section>
@endsection
