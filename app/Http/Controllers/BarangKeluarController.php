<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\barang_keluar;
use App\Models\barang;
use App\Models\gudang;

class BarangKeluarController extends Controller
{
    public function index()
    {
        $items = barang_keluar::with(['barang', 'gudang'])->orderBy('tanggal', 'desc')->get();
        $barangs = barang::orderBy('nama_barang')->get();
        $gudangs = gudang::orderBy('kode_gudang')->get();

        return view('content.barang_keluar.index', compact('items', 'barangs', 'gudangs'));
    }

    public function create()
    {
        $barangs = barang::orderBy('nama_barang')->get();
        $gudangs = gudang::orderBy('kode_gudang')->get();
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

        barang_keluar::create([
            'id_barang' => $request->id_barang,
            'kode_gudang' => $request->kode_gudang,
            'jumlah' => $request->jumlah,
            'tanggal' => $request->tanggal,
        ]);

        return redirect()->route('barang-keluar-index')->with('success', 'Barang keluar berhasil ditambahkan!');
    }

    public function update(Request $request, barang_keluar $barang_keluar)
    {
        $request->validate([
            'id_barang' => 'required|exists:barang,id',
            'kode_gudang' => 'required|exists:gudang,kode_gudang',
            'jumlah' => 'required|integer|min:1',
            'tanggal' => 'required|date',
        ]);

        $barang_keluar->update([
            'id_barang' => $request->id_barang,
            'kode_gudang' => $request->kode_gudang,
            'jumlah' => $request->jumlah,
            'tanggal' => $request->tanggal,
        ]);

        return redirect()->route('barang-keluar-index')->with('success', 'Data barang keluar berhasil diperbarui!');
    }

    public function destroy(barang_keluar $barang_keluar)
    {
        $barang_keluar->delete();
        return redirect()->route('barang-keluar-index')->with('success', 'Data barang keluar berhasil dihapus!');
    }
}
