<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\barang_masuk;
use App\Models\barang;
use App\Models\gudang;
use App\Models\supplier;
use App\Models\stok;
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

    public function create(Request $request)
    {
        $gudangKode = $request->query('gudang');
        $selectedBarang = $request->query('barang');

        $barangs = barang::orderBy('nama_barang')->get();
        $gudangs = gudang::orderBy('kode_gudang')->get();
        $suppliers = supplier::orderBy('nama_supplier')->get();

        // If accessed from a gudang page (gudang query present), show a full page form
        if ($gudangKode) {
            return view('content.barang_masuk.create_page', compact('barangs', 'gudangs', 'suppliers', 'gudangKode', 'selectedBarang'));
        }

        // Default: return the modal partial as before
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

        $created = barang_masuk::create([
            'id_barang' => $request->id_barang,
            'kode_gudang' => $request->kode_gudang,
            'id_supplier' => $request->id_supplier,
            'jumlah' => $request->jumlah,
            'tanggal' => $request->tanggal,
            'id_users' => Auth::id() ?? 1,
        ]);

        // Update stok: increment existing stok for the gudang, or create a new stok record
        $existing = stok::where('id_barang', $request->id_barang)
            ->where('kode_gudang', $request->kode_gudang)
            ->first();

        if ($existing) {
            $existing->increment('stok', (int) $request->jumlah);
        } else {
            stok::create([
                'id_barang' => $request->id_barang,
                'kode_gudang' => $request->kode_gudang,
                'stok' => (int) $request->jumlah,
            ]);
        }

        // If request expects JSON (AJAX), return JSON so client fetch can proceed reliably
        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'barang_masuk_id' => $created->id,
                'kode_gudang' => $request->kode_gudang,
            ], 201);
        }

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

    /**
     * Bulk delete barang_masuk entries.
     */
    public function bulkDestroy(Request $request)
    {
        $data = $request->validate([
            'ids' => 'required|array|min:1',
            'ids.*' => 'integer|exists:barang_masuk,id'
        ]);

        \DB::table('barang_masuk')->whereIn('id', $data['ids'])->delete();

        return redirect()->route('barang-masuk-index')->with('success', 'Data barang masuk terpilih berhasil dihapus.');
    }
}
