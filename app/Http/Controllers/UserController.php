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
        confirmDelete('Hapus User', 'Apakah anda yakin menghapus user ini?');
        return view('users.index', compact('users'));
    }

    public function store(Request $request)
    {
        $id = $request->id;

        $request->validate([
            'name'  => 'required',
            'email' => 'required|email|unique:users,email,' . $id,
        ]);

        // ❗ JANGAN PAKE $request->all()
        $data = [
            'name'  => $request->name,
            'email' => $request->email,
        ];

        // 👉 Jika user baru → set password default
        if (!$id) {
            $data['password'] = Hash::make('admin123456789');
        }

        User::updateOrCreate(
            ['id' => $id],
            $data
        );

        toast()->success('User berhasil disimpan');
        return redirect()->route('users.index');
    }

    public function destroy(string $id)
    {
        if (Auth::id() == $id) {
            toast()->error('Tidak dapat menghapus akun yang sedang login');
            return redirect()->route('users.index');
        }

        User::findOrFail($id)->delete();
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

        toast()->success('Password berhasil direset');
        return redirect()->route('users.index');
    }
}
