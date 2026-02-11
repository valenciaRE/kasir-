<div>
    <button type="button" class="btn btn-dark mx-1" data-toggle="modal" data-target="#resetPassword{{ $id }}">
        <i class="fas fa-lock-open"></i>
    </button>

    <div class="modal fade" id="resetPassword{{ $id }}" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="{{ route('users.reset-password') }}" method="POST">
                    @csrf
                    <input type="hidden" name="id" value="{{ $id }}">

                    <div class="modal-header">
                        <h5 class="modal-title">Reset Password</h5>
                        <button type="button" class="close" data-dismiss="modal">
                            <span>&times;</span>
                        </button>
                    </div>

                    <div class="modal-body">
                        <p>
                            Password user akan direset menjadi:
                            <strong>admin123456789</strong>
                        </p>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-danger">Reset</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
