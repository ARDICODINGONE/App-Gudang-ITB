<?php

namespace App\Http\Controllers;

use App\Models\Kategori;
use Illuminate\Http\Request;

class KategoriController extends Controller
{
    // TAMPIL DATA
    public function index()
    {
        $kategoris = Kategori::orderBy('kategori', 'asc')->get();
        return view('content.kategori.index', compact('kategoris'));
    }

    // SIMPAN DATA
    public function store(Request $request)
    {
        $request->validate([
            'kategori' => 'required|string|max:100'
        ]);

        Kategori::create([
            'kategori' => $request->kategori
        ]);

        return redirect()->back()->with('success', 'Kategori berhasil ditambahkan');
    }

    // UPDATE DATA
    public function update(Request $request, Kategori $kategori)
    {
        $request->validate([
            'kategori' => 'required|string|max:100'
        ]);

        $kategori->update([
            'kategori' => $request->kategori
        ]);

        return redirect()->back()->with('success', 'Kategori berhasil diupdate');
    }

    // HAPUS DATA
    public function destroy(Kategori $kategori)
    {
        $kategori->delete();
        return redirect()->back()->with('success', 'Kategori berhasil dihapus');
    }
}
