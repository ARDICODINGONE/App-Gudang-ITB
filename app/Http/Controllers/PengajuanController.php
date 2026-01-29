<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Gudang;
use App\Models\Stok;
use App\Models\Cart;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Carbon\Carbon;

class PengajuanController extends Controller
{
    public function index(Request $request)
    {
        $gudang = gudang::all();
        $stok = null;
        $selectedGudang = null;

        // If coming from cart, load cart items and try to pick a gudang
        if ($request->filled('from_cart')) {
            // find cart (by user or session)
            if (Auth::check()) {
                $cart = Cart::firstOrCreate(['user_id' => Auth::id()]);
            } else {
                $sessionId = $request->session()->getId();
                $cart = Cart::firstOrCreate(['session_id' => $sessionId]);
            }

            $cartItems = $cart->items()->with('barang')->get();

            // prepare stok-like objects from cart
            $stok = $cartItems->map(function ($it) use ($request) {
                $barang = $it->barang;
                if ($barang) {
                    $barang->nama = $barang->nama_barang ?? ($barang->nama ?? null);
                }

                return (object) [
                    'barang' => $barang,
                    'requested' => $it->quantity,
                    'qty' => $it->quantity,
                    'barang_id' => $it->barang_id,
                    'available' => null,
                ];
            });

            // determine gudang that has stock for all items (by kode_gudang)
            $arrays = [];
            foreach ($cartItems as $it) {
                $guds = Stok::where('id_barang', $it->barang_id)
                    ->where('stok', '>', 0)
                    ->pluck('kode_gudang')
                    ->unique()
                    ->toArray();
                $arrays[] = $guds;
            }

            if (!empty($arrays)) {
                $inter = array_shift($arrays);
                foreach ($arrays as $a) {
                    $inter = array_values(array_intersect($inter, $a));
                }
                if (!empty($inter)) {
                    $selectedGudang = $inter[0];
                }
            }
        }

        // If a gudang is selected (either by user or auto), compute available stok per item
        $kodeGudang = $request->input('gudang_id') ?? ($selectedGudang ?? null);
        if ($kodeGudang && isset($stok) && $stok) {
            $stok = $stok->map(function ($s) use ($kodeGudang) {
                $avail = \App\Models\Stok::where('kode_gudang', $kodeGudang)
                    ->where('id_barang', $s->barang_id)
                    ->value('stok');
                $s->available = $avail !== null ? (int) $avail : 0;
                // ensure requested field exists
                if (!isset($s->requested)) $s->requested = 0;
                return $s;
            });
        }

        if ($request->filled('gudang_id')) {
            $kode = $request->gudang_id;
            $stokRows = Stok::where('kode_gudang', $kode)->with('barang')->get();

            $stok = $stokRows->map(function ($s) {
                $barang = $s->barang;
                if ($barang) {
                    $barang->nama = $barang->nama_barang ?? ($barang->nama ?? null);
                }

                return (object) [
                    'barang' => $barang,
                    'qty' => $s->stok ?? ($s->qty ?? 0),
                    'barang_id' => $s->id_barang ?? ($barang->id ?? null),
                ];
            });
        }

        return view('content.pengajuan.index', compact('gudang', 'stok', 'selectedGudang'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'gudang_id' => 'required',
            'items' => 'required|array',
        ]);

        $items = array_filter($request->input('items', []), function ($v) {
            return (int) $v > 0;
        });

        if (empty($items)) {
            return back()->with('error', 'Pilih minimal 1 barang.');
        }

        DB::beginTransaction();
        try {
            $kode = 'PJ' . Carbon::now()->format('YmdHis') . Str::upper(Str::random(4));

            $pengajuanId = DB::table('pengajuan')->insertGetId([
                'kode_pengajuan' => $kode,
                'user_id' => Auth::id(),
                'kode_gudang' => $request->gudang_id,
                'jumlah' => array_sum($items),
                'tanggal' => Carbon::now()->toDateString(),
                'status' => 'pending',
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            foreach ($items as $barangId => $jumlah) {
                DB::table('pengajuan_detail')->insert([
                    'pengajuan_id' => $pengajuanId,
                    'barang_id' => $barangId,
                    'jumlah' => $jumlah,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            DB::commit();

            return redirect()->route('pengajuan.index')->with('success', 'Pengajuan berhasil dibuat.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    // Create pengajuan directly from current cart and clear the cart
    public function fromCart(Request $request)
    {
        // find cart (by user or session)
        if (Auth::check()) {
            $cart = Cart::firstOrCreate(['user_id' => Auth::id()]);
        } else {
            $sessionId = $request->session()->getId();
            $cart = Cart::firstOrCreate(['session_id' => $sessionId]);
        }

        $cartItems = $cart->items()->get();
        if ($cartItems->isEmpty()) {
            return redirect('/cart')->with('error', 'Keranjang kosong.');
        }

        // find gudang that has stock for all items (reuse index logic)
        $arrays = [];
        foreach ($cartItems as $it) {
            $guds = Stok::where('id_barang', $it->barang_id)
                ->where('stok', '>', 0)
                ->pluck('kode_gudang')
                ->unique()
                ->toArray();
            $arrays[] = $guds;
        }

        $selectedGudang = null;
        if (!empty($arrays)) {
            $inter = array_shift($arrays);
            foreach ($arrays as $a) {
                $inter = array_values(array_intersect($inter, $a));
            }
            if (!empty($inter)) {
                $selectedGudang = $inter[0];
            }
        }

        // fallback: try any gudang that has stock for the first item
        if (!$selectedGudang) {
            $first = $cartItems->first();
            if ($first) {
                $selectedGudang = Stok::where('id_barang', $first->barang_id)
                    ->where('stok', '>', 0)
                    ->value('kode_gudang');
            }
        }

        if (!$selectedGudang) {
            return back()->with('error', 'Tidak ada gudang dengan stok yang tersedia untuk item di keranjang.');
        }

        $items = [];
        foreach ($cartItems as $it) {
            $items[$it->barang_id] = $it->quantity;
        }

        DB::beginTransaction();
        try {
            $kode = 'PJ' . Carbon::now()->format('YmdHis') . Str::upper(Str::random(4));

            $pengajuanId = DB::table('pengajuan')->insertGetId([
                'kode_pengajuan' => $kode,
                'user_id' => Auth::id(),
                'kode_gudang' => $selectedGudang,
                'jumlah' => array_sum($items),
                'tanggal' => Carbon::now()->toDateString(),
                'status' => 'pending',
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            foreach ($items as $barangId => $jumlah) {
                DB::table('pengajuan_detail')->insert([
                    'pengajuan_id' => $pengajuanId,
                    'barang_id' => $barangId,
                    'jumlah' => $jumlah,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            // clear cart items
            $cart->items()->delete();

            DB::commit();

            return redirect()->route('pengajuan.list')->with('success', 'Pengajuan berhasil dibuat dari keranjang.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function list(Request $request)
    {
        $items = DB::table('pengajuan')->orderBy('created_at', 'desc')->paginate(20);
        return view('content.pengajuan.list', compact('items'));
    }

    public function show($id)
    {
        $pengajuan = DB::table('pengajuan')->where('id', $id)->first();
        $details = DB::table('pengajuan_detail as pd')
            ->leftJoin('barang as b', 'pd.barang_id', '=', 'b.id')
            ->where('pd.pengajuan_id', $id)
            ->select('pd.*', 'b.nama_barang')
            ->get();

        return view('content.pengajuan.show', compact('pengajuan', 'details'));
    }

    public function approve(Request $request, $id)
    {
        if (!Auth::check() || !in_array(Auth::user()->role, ['approval', 'atasan', 'admin'])) {
            abort(403);
        }

        $pengajuan = DB::table('pengajuan')->where('id', $id)->first();
        if (!$pengajuan) {
            return back()->with('error', 'Pengajuan tidak ditemukan.');
        }

        if ($pengajuan->status !== 'pending') {
            return back()->with('error', 'Pengajuan sudah diproses.');
        }

        DB::beginTransaction();
        try {
            // check stok availability
            $details = DB::table('pengajuan_detail')->where('pengajuan_id', $id)->get();
            foreach ($details as $d) {
                $stokRow = DB::table('stok')
                    ->where('kode_gudang', $pengajuan->kode_gudang)
                    ->where('id_barang', $d->barang_id)
                    ->first();

                if (!$stokRow || ($stokRow->stok ?? 0) < $d->jumlah) {
                    throw new \Exception('Stok tidak cukup untuk barang ID: ' . $d->barang_id);
                }
            }

            // deduct stok
            foreach ($details as $d) {
                DB::table('stok')
                    ->where('kode_gudang', $pengajuan->kode_gudang)
                    ->where('id_barang', $d->barang_id)
                    ->decrement('stok', $d->jumlah);
            }

            DB::table('pengajuan')->where('id', $id)->update([
                'status' => 'approved',
                'updated_at' => now(),
            ]);

            DB::commit();

            return redirect()->route('pengajuan.show', $id)->with('success', 'Pengajuan disetujui dan stok diperbarui.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal menyetujui: ' . $e->getMessage());
        }
    }

    public function reject(Request $request, $id)
    {
        if (!Auth::check() || !in_array(Auth::user()->role, ['approval', 'atasan', 'admin'])) {
            abort(403);
        }

        $pengajuan = DB::table('pengajuan')->where('id', $id)->first();
        if (!$pengajuan) {
            return back()->with('error', 'Pengajuan tidak ditemukan.');
        }

        if ($pengajuan->status !== 'pending') {
            return back()->with('error', 'Pengajuan sudah diproses.');
        }

        DB::table('pengajuan')->where('id', $id)->update([
            'status' => 'rejected',
            'updated_at' => now(),
            'note' => $request->input('note') ?? null,
        ]);

        return redirect()->route('pengajuan.show', $id)->with('success', 'Pengajuan ditolak.');
    }
}
