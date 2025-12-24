<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\barang;
use App\Models\kategori;

class BarangController extends Controller
{
    public function index()
    {
        $barangs = barang::with('kategori')->get();
        $kategoris = kategori::all();
        return view('content.barang.index', compact('barangs', 'kategoris'));
    }

    public function create()
    {
        $kategoris = kategori::all();
        return view('content.barang.create', compact('kategoris'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'kode_barang' => 'required|unique:barang,kode_barang|max:10',
            'nama_barang' => 'required|string|max:255',
            'kategori_id' => 'required|exists:kategori,id',
            'satuan' => 'required|string|max:50',
        ], [
            'kode_barang.unique' => 'Kode Barang sudah ada, gunakan kode lain.',
            'kode_barang.required' => 'Kode Barang wajib diisi.',
        ]);

        barang::create([
            'kode_barang' => $request->kode_barang,
            'nama_barang' => $request->nama_barang,
            'kategori_id' => $request->kategori_id,
            'satuan' => $request->satuan,
        ]);

        return redirect()->route('barang-index')->with('success', 'Barang berhasil ditambahkan!');
    }

    public function update(Request $request, $kode_barang)
    {
        // 1. Validasi
        $request->validate([
            'nama_barang' => 'required|string|max:255',
            'kategori_id' => 'required|exists:kategori,id',
            'satuan' => 'required|string|max:50',
            // Kode barang biasanya tidak boleh diedit karena Primary Key, jadi tidak divalidasi unique lagi
        ]);

        // 2. Cari Data
        $barang = barang::where('kode_barang', $kode_barang)->firstOrFail();

        // 3. Update Data
        $barang->update([
            'nama_barang' => $request->nama_barang,
            'kategori_id' => $request->kategori_id,
            'satuan' => $request->satuan,
        ]);

        // 4. Redirect
        return redirect()->route('barang-index')->with('success', 'Data barang berhasil diperbarui!');
    }

    public function destroy($kode_barang)
    {
        $barang = barang::where('kode_barang', $kode_barang)->firstOrFail();
        $barang->delete();
        return redirect()->route('barang-index')->with('success', 'Data barang berhasil dihapus!');
    }
}
