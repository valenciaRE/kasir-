<div>
    <button type="button" class="btn {{ $id ? 'btn-warning' : 'btn-primary'}}" data-toggle="modal" data-target="#formUser{{ $id ?? '' }}">
        @if ($id)
            <i class="fas fa-edit"></i>
        @else
            User Baru
        @endif
    </button>

    <div class="modal fade" id="formUser{{ $id ?? '' }}" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <form action="{{ route('users.store') }}" method="POST">
                    @csrf
                    <input type="hidden" name="id" value="{{ $id ?? '' }}">

                    <div class="modal-header">
                        <h4 class="modal-title">{{ $id ? 'Form Edit User' : 'Form User Baru' }}</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>

                    <div class="modal-body text-left"> <div class="form-group mb-3">
                            <label for="email{{ $id }}">Email</label>
                            <input type="email" name="email" id="email{{ $id }}" class="form-control" 
                                   value="{{ $id ? $email : old('email') }}" placeholder="Masukkan Email" required>
                        </div>
                        
                        <div class="form-group mb-3">
                            <label for="name{{ $id }}">Nama</label>
                            <input type="text" name="name" id="name{{ $id }}" class="form-control" 
                                   value="{{ $id ? $name : old('name') }}" placeholder="Masukkan Nama" required>
                        </div>
                    </div>

                    <div class="modal-footer justify-content-between">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>