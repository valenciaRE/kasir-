<div>
    <div class="modal fade" id="formGantiPassword" tabindex="-1" aria-labelledby="formGantiPasswordLabel" aria-hidden="true">
        {{-- Form action sudah benar mengarah ke route ganti-password --}}
        <form action="{{ route('users.ganti-password') }}" method="POST">
            @csrf
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header bg-primary text-white">
                        <h5 class="modal-title" id="formGantiPasswordLabel">Form Ganti Password</h5>
                        <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        {{-- Password Lama --}}
                        <div class="form-group my-1">
                            <label>Password Lama</label>
                            <input type="password" name="password_lama" id="password_lama" class="form-control" required>
                            @error('password_lama')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        {{-- Password Baru --}}
                        <div class="form-group my-1">
                            <label>Password Baru</label>
                            <input type="password" name="password_baru" id="password_baru" class="form-control" required>
                            @error('password_baru')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        {{-- Konfirmasi Password Baru --}}
                        <div class="form-group my-1">
                            <label>Konfirmasi Password Baru</label>
                            <input type="password" name="konfirmasi_password" id="konfirmasi_password" class="form-control" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>