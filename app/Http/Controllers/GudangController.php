<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Gudang; // PascalCase

class GudangController extends Controller
{
    public function index()
    {
        $gudangs = Gudang::orderBy('kode_gudang')->get();
        return view('content.gudang.index', compact('gudangs')); // 'gudangs' bukan 'gudang'
    }

    public function store(Request $request)
    {
        $request->validate([
            'kode_gudang' => 'required|unique:gudang,kode_gudang|max:10',
            'nama_gudang' => 'required|string|max:255',
            'lokasi' => 'required|string|max:255',
        ], [
            'kode_gudang.unique' => 'Kode Gudang sudah ada, gunakan kode lain.',
            'kode_gudang.required' => 'Kode Gudang wajib diisi.',
        ]);

        Gudang::create([
            'kode_gudang' => $request->kode_gudang,
            'nama_gudang' => $request->nama_gudang,
            'lokasi' => $request->lokasi,
        ]);

        return redirect()->route('gudang-index')->with('success', 'Gudang berhasil ditambahkan!');
    }

    public function update(Request $request, $kode_gudang)
    {
        // 1. Validasi
        $request->validate([
            'nama_gudang' => 'required|string|max:255',
            'lokasi' => 'required|string|max:255',
            // Kode gudang biasanya tidak boleh diedit karena Primary Key, jadi tidak divalidasi unique lagi
        ]);

        // 2. Cari Data
        $gudang = Gudang::where('kode_gudang', $kode_gudang)->firstOrFail();

        // 3. Update Data
        $gudang->update([
            'nama_gudang' => $request->nama_gudang,
            'lokasi' => $request->lokasi,
        ]);

        // 4. Redirect
        return redirect()->route('gudang-index')->with('success', 'Data gudang berhasil diperbarui!');
    }

    public function destroy($kode_gudang)
    {
        // Cari data berdasarkan kode_gudang
        // Kita gunakan firstOrFail agar muncul 404 jika data tidak ada
        $gudang = Gudang::where('kode_gudang', $kode_gudang)->firstOrFail();

        // Hapus data
        $gudang->delete();

        // Redirect kembali
        return redirect()->route('gudang-index')->with('success', 'Data gudang berhasil dihapus!');
    }
}