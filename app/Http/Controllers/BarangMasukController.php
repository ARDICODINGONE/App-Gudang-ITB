<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\barang_masuk;
use App\Models\barang;
use App\Models\gudang;
use App\Models\supplier;
use Illuminate\Support\Facades\Auth;

class BarangMasukController extends Controller
{
    public function index()
    {
        $items = barang_masuk::with(['barang', 'gudang', 'supplier', 'user'])->orderBy('tanggal', 'desc')->get();
        $barangs = barang::orderBy('nama_barang')->get();
        $gudangs = gudang::orderBy('kode_gudang')->get();
        $suppliers = supplier::orderBy('nama_supplier')->get();

        return view('content.barang_masuk.index', compact('items', 'barangs', 'gudangs', 'suppliers'));
    }

    public function create()
    {
        $barangs = barang::orderBy('nama_barang')->get();
        $gudangs = gudang::orderBy('kode_gudang')->get();
        $suppliers = supplier::orderBy('nama_supplier')->get();
        return view('content.barang_masuk.create', compact('barangs', 'gudangs', 'suppliers'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_barang' => 'required|exists:barang,id',
            'kode_gudang' => 'required|exists:gudang,kode_gudang',
            'id_supplier' => 'required|exists:supplier,id',
            'jumlah' => 'required|integer|min:1',
            'tanggal' => 'required|date',
        ]);

        barang_masuk::create([
            'id_barang' => $request->id_barang,
            'kode_gudang' => $request->kode_gudang,
            'id_supplier' => $request->id_supplier,
            'jumlah' => $request->jumlah,
            'tanggal' => $request->tanggal,
            'id_users' => Auth::id() ?? 1,
        ]);

        return redirect()->route('barang-masuk-index')->with('success', 'Barang masuk berhasil ditambahkan!');
    }

    public function update(Request $request, barang_masuk $barang_masuk)
    {
        $request->validate([
            'id_barang' => 'required|exists:barang,id',
            'kode_gudang' => 'required|exists:gudang,kode_gudang',
            'id_supplier' => 'required|exists:supplier,id',
            'jumlah' => 'required|integer|min:1',
            'tanggal' => 'required|date',
        ]);

        $barang_masuk->update([
            'id_barang' => $request->id_barang,
            'kode_gudang' => $request->kode_gudang,
            'id_supplier' => $request->id_supplier,
            'jumlah' => $request->jumlah,
            'tanggal' => $request->tanggal,
        ]);

        return redirect()->route('barang-masuk-index')->with('success', 'Data barang masuk berhasil diperbarui!');
    }

    public function destroy(barang_masuk $barang_masuk)
    {
        $barang_masuk->delete();
        return redirect()->route('barang-masuk-index')->with('success', 'Data barang masuk berhasil dihapus!');
    }
}
