@extends('layouts.app')

@section('content_title')
Data Kategori
@endsection

@section('content')
<div class="card">
    <div class="card-header border-bottom-0">
        <h3 class="card-title">Data Kategori</h3>
        <div class="card-tools">
            <x-kategori.form-kategori />
        </div>
    </div>

    <div class="card-body">
        @if ($errors->any())
        <div class="alert alert-danger d-flex flex-column p-2">
            @foreach ($errors->all() as $error)
                <small class="text-white my-1"><i class="fas fa-exclamation-triangle mr-1"></i> {{ $error }}</small>
            @endforeach
        </div>
        @endif

        <table id="table1" class="table table-bordered table-striped table-hover">
            <thead>
                <tr>
                    <th style="width: 40px" class="text-center">No</th>
                    <th>Nama Kategori</th>
                    <th>Deskripsi</th>
                    <th style="width: 100px" class="text-center">Opsi</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($kategoris as $index => $kategori)
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td>{{ $kategori->nama_kategori }}</td>
                    <td>{{ $kategori->deskripsi }}</td>
                    <td>
                        <div class="d-flex justify-content-center align-items-center">
                             <div class="mr-1">
                                <x-kategori.form-kategori :id="$kategori->id" />
                             </div>
                             
                             <a href="{{ route('master-data.kategori.destroy', $kategori->id) }}"
                               data-confirm-delete="true"
                               class="btn btn-danger mx-1">
                                 <i class="fas fa-trash"></i>
                             </a>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection