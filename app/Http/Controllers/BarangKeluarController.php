<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BarangKeluar;
use App\Models\Barang;
use App\Models\Gudang;
use App\Models\Stok;

class BarangKeluarController extends Controller
{
    public function index()
    {
        $items = BarangKeluar::with(['barang', 'gudang'])->orderBy('tanggal', 'desc')->get();
        $barangs = Barang::orderBy('nama_barang')->get();
        $gudangs = Gudang::orderBy('kode_gudang')->get();

        return view('content.barang_keluar.index', compact('items', 'barangs', 'gudangs'));
    }

    public function create()
    {
        $barangs = Barang::orderBy('nama_barang')->get();
        $gudangs = Gudang::orderBy('kode_gudang')->get();
        return view('content.barang_keluar.create', compact('barangs', 'gudangs'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_barang' => 'required|exists:barang,id',
            'kode_gudang' => 'required|exists:gudang,kode_gudang',
            'jumlah' => 'required|integer|min:1',
            'tanggal' => 'required|date',
        ]);

        BarangKeluar::create([
            'id_barang' => $request->id_barang,
            'kode_gudang' => $request->kode_gudang,
            'jumlah' => $request->jumlah,
            'tanggal' => $request->tanggal,
        ]);

        // Update stok: decrement existing stok for the gudang
        $existing = Stok::where('id_barang', $request->id_barang)
            ->where('kode_gudang', $request->kode_gudang)
            ->first();

        if ($existing) {
            $existing->decrement('stok', (int) $request->jumlah);
        }

        return redirect()->route('barang-keluar-index')->with('success', 'Barang keluar berhasil ditambahkan!');
    }

    public function update(Request $request, BarangKeluar $barangKeluar)
    {
        $request->validate([
            'id_barang' => 'required|exists:barang,id',
            'kode_gudang' => 'required|exists:gudang,kode_gudang',
            'jumlah' => 'required|integer|min:1',
            'tanggal' => 'required|date',
        ]);

        $barangKeluar->update([
            'id_barang' => $request->id_barang,
            'kode_gudang' => $request->kode_gudang,
            'jumlah' => $request->jumlah,
            'tanggal' => $request->tanggal,
        ]);

        return redirect()->route('barang-keluar-index')->with('success', 'Data barang keluar berhasil diperbarui!');
    }

    public function destroy(BarangKeluar $barangKeluar)
    {
        $barangKeluar->delete();
        return redirect()->route('barang-keluar-index')->with('success', 'Data barang keluar berhasil dihapus!');
    }
}
