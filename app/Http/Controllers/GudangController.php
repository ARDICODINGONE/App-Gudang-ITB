<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Gudang; // PascalCase
use Illuminate\Support\Facades\Storage;

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
            'images' => 'nullable|image|mimes:jpg,jpeg,png,gif|max:2048',
        ], [
            'kode_gudang.unique' => 'Kode Gudang sudah ada, gunakan kode lain.',
            'kode_gudang.required' => 'Kode Gudang wajib diisi.',
        ]);

        $imagesPath = null;
        if ($request->hasFile('images')) {
            $imagesPath = $request->file('images')->store('gudang', 'public');
        }

        Gudang::create([
            'kode_gudang' => $request->kode_gudang,
            'nama_gudang' => $request->nama_gudang,
            'lokasi' => $request->lokasi,
            'images' => $imagesPath,
        ]);

        return redirect()->route('gudang-index')->with('success', 'Gudang berhasil ditambahkan!');
    }

    public function update(Request $request, $kode_gudang)
    {
        // 1. Validasi
        $request->validate([
            'nama_gudang' => 'required|string|max:255',
            'lokasi' => 'required|string|max:255',
            'images' => 'nullable|image|mimes:jpg,jpeg,png,gif|max:2048',
            // Kode gudang biasanya tidak boleh diedit karena Primary Key, jadi tidak divalidasi unique lagi
        ]);

        // 2. Cari Data
        $gudang = Gudang::where('kode_gudang', $kode_gudang)->firstOrFail();

        // 3. Update Data
        $data = [
            'nama_gudang' => $request->nama_gudang,
            'lokasi' => $request->lokasi,
        ];

        if ($request->hasFile('images')) {
            // hapus file lama jika ada
            if ($gudang->images) {
                Storage::disk('public')->delete($gudang->images);
            }
            $data['images'] = $request->file('images')->store('gudang', 'public');
        }

        $gudang->update($data);

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