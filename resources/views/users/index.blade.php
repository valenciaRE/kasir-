@extends('layouts.app')

@section('content_title', 'Data Users')

@section('content')
<div class="card">
    <div class="p-2 d-flex justify-content-between border">
        <h3 class="h5">Data Users</h3>
        <div>
            <x-user.form-user />
        </div>
    </div>

    <div class="card-body">
        <x-alert :errors="$errors" />
        <table class="table table-sm" id="table-users">
            <thead class="text-center">
                <tr>
                    <th>No</th>
                    <th>Email</th>
                    <th>Nama Users</th>
                    <th>Opsi</th>
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
                                {{-- EDIT --}}
                                <x-user.form-user :id="$user->id" :email="$user->email" :name="$user->name" />

                                {{-- DELETE --}}
                                <form action="{{ route('users.destroy', $user->id) }}" method="POST" id="delete-form-{{ $user->id }}" style="display:inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button" class="btn btn-danger mx-1 btn-delete" data-id="{{ $user->id }}">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>

                                {{-- RESET PASSWORD --}}
                                <x-user.reset-password :id="$user->id" />
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection

@push('scripts')
{{-- Load SweetAlert2 --}}
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    $(function () {
        // Inisialisasi DataTable
        if (!$.fn.DataTable.isDataTable('#table-users')) {
            $('#table-users').DataTable({
                responsive: true,
                autoWidth: false,
                lengthChange: true,
            });
        }

        // Handle SweetAlert untuk Delete
        $(document).off('click', '.btn-delete').on('click', '.btn-delete', function (e) {
            e.preventDefault();
            let id = $(this).data('id');
            
            Swal.fire({
                title: 'Hapus User',
                text: "Apakah anda yakin menghapus user ini?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Saya Yakin, Hapus Data ini',
                cancelButtonText: 'Batal',
                reverseButtons: false
            }).then((result) => {
                if (result.isConfirmed) {
                    $(`#delete-form-${id}`).submit();
                }
            });
        });
    });
</script>
@endpush