<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Gudang;
use App\Models\Barang;
use App\Models\Kategori;
use App\Models\Stok;
use App\Models\Supplier;
use Illuminate\Support\Facades\Storage;

class GudangController extends Controller
{
    public function index(Request $request)
    {
        // If a specific gudang code is requested, show its product listing page
        $kode = $request->query('kode');
        if ($kode) {
            $gudangKode = $kode;
            $kategoris = Kategori::all();

            // Get barang that have stok records for this gudang
            $barangs = Barang::with(['kategori', 'stok' => function($q) use ($kode) {
                $q->where('kode_gudang', $kode);
            }])->whereHas('stok', function ($q) use ($kode) {
                $q->where('kode_gudang', $kode);
            })->orderBy('nama_barang')->get();
            // also pass the full barang index so the "Pilih Barang" modal shows all items
            $allBarangs = Barang::orderBy('nama_barang')->get();
            $gudangs = Gudang::orderBy('kode_gudang')->get();
            $suppliers = Supplier::orderBy('nama_supplier')->get();
            return view('shop', compact('barangs', 'kategoris', 'gudangKode', 'gudangs', 'suppliers', 'allBarangs'));
        }

        $gudangs = Gudang::orderBy('kode_gudang')->get();

        $last = Gudang::orderBy('kode_gudang', 'desc')->first();
        if (!$last) {
            $nextKode = 'GD-001';
        } else {
            $parts = explode('-', $last->kode_gudang);
            $num = 0;
            if (count($parts) > 1 && is_numeric(end($parts))) {
                $num = intval(end($parts));
            } else {
                preg_match('/(\d+)$/', $last->kode_gudang, $matches);
                $num = isset($matches[1]) ? intval($matches[1]) : 0;
            }
            $num++;
            $nextKode = sprintf('%s-%03d', $parts[0] ?? 'GD', $num);
        }

        return view('content.gudang.index', compact('gudangs', 'nextKode')); // 'gudangs' bukan 'gudang'
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

    /**
     * Add multiple existing products to a gudang's stok.
     * Expects JSON: { product_ids: [1,2,3], initial_stock: 0 }
     */
    // addProducts removed — functionality deprecated when UI picker/bulk-delete removed

    /**
     * Remove multiple products from a gudang's stok.
     * Expects JSON: { product_ids: [1,2,3] }
     */
    // removeProducts removed — functionality deprecated when UI picker/bulk-delete removed
    
}