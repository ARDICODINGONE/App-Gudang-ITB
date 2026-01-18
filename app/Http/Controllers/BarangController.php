<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\barang;
use App\Models\kategori;
use App\Models\stok;
use App\Models\gudang;
use App\Models\barang_masuk;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class BarangController extends Controller
{
    public function index(Request $request)
    {
        $gudangKode = $request->query('gudang');

        if ($gudangKode) {
            $barangs = barang::with(['kategori', 'stok' => function ($q) use ($gudangKode) {
                $q->where('kode_gudang', $gudangKode);
            }])->whereHas('stok', function ($q) use ($gudangKode) {
                $q->where('kode_gudang', $gudangKode);
            })->get();
        } else {
            $barangs = barang::with('kategori')->get();
        }

        $kategoris = kategori::all();

        // compute next kode_barang so modal can render it server-side (no client delay)
        $last = barang::where('kode_barang', 'like', 'BR%')
            ->orderByRaw('LENGTH(kode_barang) desc, kode_barang desc')
            ->first();

        $num = 0;
        if ($last && preg_match('/BR0*([0-9]+)$/', $last->kode_barang, $m)) {
            $num = (int) $m[1];
        }
        $nextKode = 'BR' . str_pad($num + 1, 3, '0', STR_PAD_LEFT);

        return view('content.barang.index', compact('barangs', 'kategoris', 'gudangKode', 'nextKode'));
    }

    public function create()
    {
        $kategoris = kategori::all();
        $gudangs = gudang::all();
        // compute next kode_barang for direct create page as well
        $last = barang::where('kode_barang', 'like', 'BR%')
            ->orderByRaw('LENGTH(kode_barang) desc, kode_barang desc')
            ->first();

        $num = 0;
        if ($last && preg_match('/BR0*([0-9]+)$/', $last->kode_barang, $m)) {
            $num = (int) $m[1];
        }
        $nextKode = 'BR' . str_pad($num + 1, 3, '0', STR_PAD_LEFT);

        return view('content.barang.create', compact('kategoris', 'gudangs', 'nextKode'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'kode_barang' => 'required|unique:barang,kode_barang|max:10',
            'nama_barang' => 'required|string|max:255',
            'kategori_id' => 'required|exists:kategori,id',
            'gudang' => 'nullable|exists:gudang,kode_gudang',
            'satuan' => 'required|string|max:50',
            'deskripsi' => 'nullable|string',
            'harga' => 'required|numeric',
            'image' => 'nullable|image|max:2048',
        ], [
            'kode_barang.unique' => 'Kode Barang sudah ada, gunakan kode lain.',
            'kode_barang.required' => 'Kode Barang wajib diisi.',
        ]);

        $data = $request->only(['kode_barang', 'nama_barang', 'kategori_id', 'satuan', 'deskripsi', 'harga']);

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('images/barang', 'public');
        }

        $new = barang::create($data);

        if ($request->filled('gudang')) {
            stok::create([
                'id_barang' => $new->id,
                'kode_gudang' => $request->gudang,
                // initial_stock removed from form: set initial stok to 0 by default
                'stok' => 0,
            ]);
            // Redirect back to the gudang page (uses query param 'kode') so the shop view for that gudang
            // shows the newly added item immediately after redirect.
            return redirect()->route('barang-index', ['gudang' => $request->gudang])->with('success', 'Barang berhasil ditambahkan!');

        }

        return redirect()->route('barang-index')->with('success', 'Barang berhasil ditambahkan!');
    }

    public function update(Request $request, $kode_barang)
    {
        $request->validate([
            'nama_barang' => 'required|string|max:255',
            'kategori_id' => 'required|exists:kategori,id',
            'satuan' => 'required|string|max:50',
            'deskripsi' => 'nullable|string',
            'harga' => 'required|numeric',
            'image' => 'nullable|image|max:2048',
        ]);

        $barang = barang::where('kode_barang', $kode_barang)->firstOrFail();

        $data = $request->only(['nama_barang', 'kategori_id', 'satuan', 'deskripsi', 'harga']);

        if ($request->hasFile('image')) {
            if ($barang->image) {
                Storage::disk('public')->delete($barang->image);
            }
            $data['image'] = $request->file('image')->store('images/barang', 'public');
        }

        $barang->update($data);

        // Update stok for given gudang if provided
        if ($request->filled('gudang') && $request->filled('stok')) {
            stok::updateOrCreate([
                'id_barang' => $barang->id,
                'kode_gudang' => $request->gudang,
            ], [
                'stok' => (int) $request->stok,
            ]);
        }

        return redirect()->route('barang-index')->with('success', 'Data barang berhasil diperbarui!');

        $request->validate([
            'nama_barang' => 'required|string',
            'deskripsi' => 'required|string',
            'kategori_id' => 'required',
            'satuan' => 'required',
            'harga' => 'required|numeric|min:1',
        ]);

    }

    public function destroy($kode_barang)
    {
        $barang = barang::where('kode_barang', $kode_barang)->firstOrFail();
        if ($barang->image) {
            Storage::disk('public')->delete($barang->image);
        }
        $barang->delete();
        return redirect()->route('barang-index')->with('success', 'Data barang berhasil dihapus!');
    }

    /**
     * Return JSON list of products for the shop frontend.
     */
    public function apiIndex(Request $request)
    {
        $gudangKode = $request->query('gudang');
        if ($gudangKode) {
            // Aggregate masuk per barang and read current stok per barang for this gudang
            $masukAgg = barang_masuk::select('id_barang', DB::raw('SUM(jumlah) as total_masuk'))
                ->where('kode_gudang', $gudangKode)
                ->groupBy('id_barang')
                ->get()
                ->keyBy('id_barang');

            $stokItems = stok::where('kode_gudang', $gudangKode)->get()->keyBy('id_barang');

            $ids = $masukAgg->keys()->all();

            $barangs = barang::with('kategori')->whereIn('id', $ids)->get();
        } else {
            $barangs = barang::with('kategori')->get();
            $masukAgg = collect();
            // Ensure $stokItems exists when no gudang filter is provided
            $stokItems = collect();
        }

        $data = $barangs->map(function ($b) use ($masukAgg, $gudangKode, $stokItems) {
            $totalMasuk = null;
            if ($gudangKode && isset($masukAgg[$b->id])) {
                $totalMasuk = (int) $masukAgg[$b->id]->total_masuk;
            }

            // Prefer actual stok table value when available, otherwise fallback to total_masuk
            $stokVal = null;
            if ($gudangKode && isset($stokItems[$b->id])) {
                $stokVal = (int) $stokItems[$b->id]->stok;
            } elseif ($totalMasuk !== null) {
                $stokVal = $totalMasuk;
            }
            return [
                'id' => $b->id,
                'kode' => $b->kode_barang,
                'name' => $b->nama_barang,
                'price' => $b->harga,
                'image' => $b->image ? asset('storage/' . $b->image) : asset('img/product-1.png'),
                'satuan' => $b->satuan,
                'deskripsi' => $b->deskripsi,
                'kategori' => $b->kategori ? $b->kategori->nama_kategori ?? null : null,
                // When filtering by gudang, include total_masuk as 'stok' so shop shows quantities based on barang_masuk
                'stok' => $stokVal,
            ];
        });

        return response()->json($data);
    }

    /**
     * Return next kode_barang in format BRnnn (e.g. BR001)
     */
    public function nextKode()
    {
        // Find the last kode_barang that starts with BR and extract numeric suffix
        $last = barang::where('kode_barang', 'like', 'BR%')
            ->orderByRaw('LENGTH(kode_barang) desc, kode_barang desc')
            ->first();

        $num = 0;
        if ($last && preg_match('/BR0*([0-9]+)$/', $last->kode_barang, $m)) {
            $num = (int) $m[1];
        }

        $next = 'BR' . str_pad($num + 1, 3, '0', STR_PAD_LEFT);

        return response()->json(['kode' => $next]);
    }

    /**
     * Check for duplicate barang by name (+ optional kategori and satuan).
     * Returns JSON { exists: bool, message: string, kode: string|null }
     */
    public function checkDuplicate(Request $request)
    {
        $name = trim($request->query('nama_barang', ''));
        $kategori = $request->query('kategori_id');
        $satuan = $request->query('satuan');

        if ($name === '') {
            return response()->json(['exists' => false]);
        }

        $query = barang::whereRaw('LOWER(nama_barang) = ?', [mb_strtolower($name)]);

        if ($kategori) {
            $query->where('kategori_id', $kategori);
        }

        if ($satuan) {
            $query->whereRaw('LOWER(satuan) = ?', [mb_strtolower($satuan)]);
        }

        $found = $query->first();

        if ($found) {
            return response()->json([
                'exists' => true,
                'message' => 'Barang dengan nama yang sama sudah ada' . ($found->kode_barang ? " (kode: {$found->kode_barang})" : ''),
                'kode' => $found->kode_barang ?? null,
            ]);
        }

        return response()->json(['exists' => false]);
    }

}
