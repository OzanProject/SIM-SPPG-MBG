@extends('layouts.app')

@section('title', 'Chart of Accounts')

@section('breadcrumb')
<ol class="breadcrumb float-sm-right">
  <li class="breadcrumb-item"><a href="{{ route('accounting.accounts.index') }}">Accounting</a></li>
  <li class="breadcrumb-item active">Chart of Accounts</li>
</ol>
@endsection

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Daftar Akun</h3>
                <div class="card-tools">
                    <a href="#" class="btn btn-primary btn-sm"><i class="fas fa-plus"></i> Tambah Akun</a>
                </div>
            </div>
            <div class="card-body table-responsive p-0">
                <table class="table table-hover text-nowrap">
                    <thead>
                        <tr>
                            <th>Kode</th>
                            <th>Nama Akun</th>
                            <th>Tipe</th>
                            <th>Saldo Normal</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($accounts as $account)
                        <tr>
                            <td>{{ $account->code }}</td>
                            <td>{{ $account->name }}</td>
                            <td>{{ ucfirst($account->type) }}</td>
                            <td>{{ ucfirst($account->normal_balance) }}</td>
                            <td>
                                <button class="btn btn-xs btn-info"><i class="fas fa-edit"></i></button>
                                <button class="btn btn-xs btn-danger"><i class="fas fa-trash"></i></button>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center">Belum ada data akun.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
