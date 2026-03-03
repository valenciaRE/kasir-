<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function index()
    {
        $users = User::all();
        // Pastikan package RealRashid/SweetAlert sudah terinstall untuk fungsi ini
        if (function_exists('confirmDelete')) {
            confirmDelete('Hapus User', 'Apakah anda yakin menghapus user ini?');
        }
        return view('users.index', compact('users'));
    }

    public function store(Request $request)
    {
        $id = $request->id;

        // 1. Validasi dengan Pesan Kustom agar tidak muncul "validation.required"
        $request->validate([
            'name'  => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,'.$id,
        ], [
            'name.required'  => 'Nama wajib diisi.',
            'email.required' => 'Email wajib diisi.',
            'email.email'    => 'Format email salah.',
            'email.unique'   => 'Email sudah digunakan user lain.',
        ]);

        $data = [
            'name'  => $request->name,
            'email' => $request->email,
        ];

        // Jika ID kosong (Tambah User Baru), berikan password default
        if (!$id) {
            $data['password'] = Hash::make('admin123456789');
        }

        // 2. Gunakan updateOrCreate untuk handle Tambah & Edit sekaligus
        User::updateOrCreate(
            ['id' => $id],
            $data
        );

        toast()->success('User berhasil disimpan');
        return redirect()->route('users.index');
    }

    public function destroy($id)
{
    // Cari user berdasarkan ID yang dikirim dari route /users/destroy/{id}
    $user = User::findOrFail($id);

    // Proteksi: Jangan biarkan user menghapus dirinya sendiri
    if (auth()->id() == $user->id) {
        toast()->error('Anda tidak bisa menghapus akun sendiri!');
        return redirect()->back();
    }

    // Proses penghapusan data dari tabel users
    $user->delete();

    // Berikan notifikasi sukses
    toast()->success('User berhasil dihapus');
    
    return redirect()->route('users.index');
}

    public function resetPassword(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:users,id',
        ]);

        User::find($request->id)->update([
            'password' => Hash::make('admin123456789'),
        ]);

        toast()->success('Password berhasil direset ke default');
        return redirect()->route('users.index');
    }

    public function gantiPassword(Request $request)
    {
        $request->validate([
            'password_lama'       => 'required',
            'password_baru'       => 'required|min:8',
            'konfirmasi_password' => 'required|same:password_baru',
        ], [
            'password_lama.required' => 'Password lama wajib diisi.',
            'password_baru.min'      => 'Password baru minimal 8 karakter.',
            'konfirmasi_password.same' => 'Konfirmasi password tidak cocok.',
        ]);

        $user = Auth::user();

        // Cek apakah password lama benar
        if (!Hash::check($request->password_lama, $user->password)) {
            toast()->error('Password lama tidak sesuai');
            return redirect()->back();
        }

        $user->update([
            'password' => Hash::make($request->password_baru),
        ]);

        toast()->success('Password berhasil diganti');
        return redirect()->route('users.index');
    }
}