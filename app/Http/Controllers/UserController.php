<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    // TAMPILKAN DATA USER
    public function index()
    {
        $users = User::orderBy('nama', 'asc')->get();
        return view('content.user.index', compact('users'));
    }

    // SIMPAN USER BARU
    public function store(Request $request)
    {
        $request->validate([
            'nama'     => 'required|string|max:100',
            'username' => 'required|string|max:50|unique:users,username',
            'password' => 'required|string|min:4',
            'role'     => 'required'
        ]);

        User::create([
            'nama'     => $request->nama,
            'username' => $request->username,
            'password' => Hash::make($request->password), // WAJIB hash
            'role'     => $request->role,
        ]);

        return redirect()->back()->with('success', 'User berhasil ditambahkan');
    }

    // UPDATE USER
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $user->update([
            'nama'     => $request->nama,
            'username' => $request->username,
            'role'     => $request->role,
        ]);

        return redirect()->back()->with('success', 'User berhasil diupdate');
    }

    // HAPUS USER
    public function destroy($id)
    {
        User::findOrFail($id)->delete();
        return redirect()->back()->with('success', 'User berhasil dihapus');
    }
}
