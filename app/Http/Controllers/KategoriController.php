<?php

namespace App\Http\Controllers;

use App\Models\Kategori;
use Illuminate\Support\Str;
use Illuminate\Http\Request;

class KategoriController extends Controller
{
    // TAMPIL DATA
    public function index()
    {
        $kategoris = Kategori::orderBy('kategori', 'asc')->get();
        return view('content.kategori.index', compact('kategoris'));
    }

    // API endpoint untuk mengambil kategori sebagai JSON
    public function apiIndex()
    {
        $kategoris = Kategori::orderBy('kategori', 'asc')->get();
        $data = $kategoris->map(function ($k) {
            return [
                'id' => $k->id,
                'name' => $k->kategori,
                'nama_kategori' => $k->kategori,
                'slug' => Str::slug($k->kategori),
            ];
        });
        return response()->json($data);
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
