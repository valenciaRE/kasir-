@extends('layouts.app')

@section('content_title', 'Data Users')

@section('content')
<div class="card">
    <div class="p-2 d-flex justify-content-between border-bottom">
        <h3 class="h5 mt-2">Data Users</h3>
        <div>
            <x-user.form-user />
        </div>
    </div>

    <div class="card-body">
        <x-alert :errors="$errors" />

        <div class="table-responsive">
            <table class="table table-sm table-hover" id="table-users">
                <thead class="text-center bg-light">
                    <tr>
                        <th width="50">No</th>
                        <th>Email</th>
                        <th>Nama Users</th>
                        <th width="150">Opsi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($users as $index => $user)
                        <tr>
                            <td class="text-center">{{ $index + 1 }}</td>
                            <td>{{ $user->email }}</td>
                            <td>{{ $user->name }}</td>
                            <td class="text-center">
                                <div class="d-flex justify-content-center align-items-center">

                                    {{-- TOMBOL EDIT --}}
                                    <x-user.form-user :id="$user->id" :email="$user->email" :name="$user->name" />

                                    {{-- TOMBOL DELETE --}}
                                    @if(auth()->id() != $user->id) 
                                        {{-- PASTIKAN ID FORM INI UNIK --}}
                                        <form action="{{ route('users.destroy', $user->id) }}" method="POST" id="delete-form-{{ $user->id }}" style="display:none;">
                                            @csrf
                                            @method('DELETE')
                                        </form>
                                        <button type="button" class="btn btn-danger btn-sm mx-1 btn-delete" data-id="{{ $user->id }}">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    @endif

                                    {{-- TOMBOL RESET PASSWORD --}}
                                    <x-user.reset-password :id="$user->id" />
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@push('scripts')
{{-- Load SweetAlert2 --}}
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
$(document).ready(function () {
    // 1. Inisialisasi DataTable
    if (!$.fn.DataTable.isDataTable('#table-users')) {
        $('#table-users').DataTable({
            responsive: true,
            autoWidth: false,
        });
    }

    // 2. Handle Klik Tombol Delete (Gunakan Delegasi Event)
    $(document).on('click', '.btn-delete', function (e) {
        e.preventDefault();
        
        // Ambil ID dari data-id tombol yang diklik
        let userId = $(this).data('id');
        console.log("Menghapus User ID:", userId); // Untuk ngecek di console F12

        Swal.fire({
            title: 'Beneran mau hapus?',
            text: "Data email dan nama user ini bakal ilang selamanya!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Ya, Hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                // Submit form secara manual berdasarkan ID-nya
                $('#delete-form-' + userId).submit();
            }
        });
    });
});
</script>
@endpush