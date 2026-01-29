<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BarangMasuk;
use App\Models\Barang;
use App\Models\Gudang;
use App\Models\Stok;
use Illuminate\Support\Facades\Auth;

class BarangMasukController extends Controller
{
    public function index()
    {
        $items = BarangMasuk::with(['barang', 'gudang', 'user'])->orderBy('tanggal', 'desc')->get();
        $barangs = Barang::orderBy('nama_barang')->get();
        $gudangs = Gudang::orderBy('kode_gudang')->get();

        return view('content.barang_masuk.index', compact('items', 'barangs', 'gudangs'));
    }

    public function create(Request $request)
    {
        $gudangKode = $request->query('gudang');
        $selectedBarang = $request->query('barang');

        $barangs = Barang::orderBy('nama_barang')->get();
        $gudangs = Gudang::orderBy('kode_gudang')->get();

        // If accessed from a gudang page (gudang query present), show a full page form
        if ($gudangKode) {
            return view('content.barang_masuk.create_page', compact('barangs', 'gudangs', 'gudangKode', 'selectedBarang'));
        }

        // Default: return the modal partial as before
        return view('content.barang_masuk.create', compact('barangs', 'gudangs'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_barang' => 'required|exists:barang,id',
            'kode_gudang' => 'required|exists:gudang,kode_gudang',
            'jumlah' => 'required|integer|min:1',
            'tanggal' => 'required|date',
        ]);

        $created = BarangMasuk::create([
            'id_barang' => $request->id_barang,
            'kode_gudang' => $request->kode_gudang,
            'jumlah' => $request->jumlah,
            'tanggal' => $request->tanggal,
            'id_users' => Auth::id() ?? 1,
        ]);

        // Update stok: increment existing stok for the gudang, or create a new stok record
        $existing = Stok::where('id_barang', $request->id_barang)
            ->where('kode_gudang', $request->kode_gudang)
            ->first();

        if ($existing) {
            $existing->increment('stok', (int) $request->jumlah);
        } else {
            Stok::create([
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

    public function update(Request $request, BarangMasuk $barang_masuk)
    {
        $request->validate([
            'id_barang' => 'required|exists:barang,id',
            'kode_gudang' => 'required|exists:gudang,kode_gudang',
            'jumlah' => 'required|integer|min:1',
            'tanggal' => 'required|date',
        ]);

        $barang_masuk->update([
            'id_barang' => $request->id_barang,
            'kode_gudang' => $request->kode_gudang,
            'jumlah' => $request->jumlah,
            'tanggal' => $request->tanggal,
        ]);

        return redirect()->route('barang-masuk-index')->with('success', 'Data barang masuk berhasil diperbarui!');
    }

    public function destroy(BarangMasuk $barang_masuk)
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
