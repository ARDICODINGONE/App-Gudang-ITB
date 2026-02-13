<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Gudang;
use App\Models\Stok;
use App\Models\Cart;
use App\Models\Pengajuan;
use App\Helpers\NotificationHelper;
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
                'note' => $request->input('note') ?? null,
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

            // Send notification to approvers
            $pengajuan = Pengajuan::find($pengajuanId);
            NotificationHelper::notifyApproversOnPengajuanSubmitted($pengajuan);

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
                'note' => $request->input('note') ?? null,
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

            // Send notification to approvers
            $pengajuan = Pengajuan::find($pengajuanId);
            NotificationHelper::notifyApproversOnPengajuanSubmitted($pengajuan);

            return redirect()->route('pengajuan.list')->with('success', 'Pengajuan berhasil dibuat dari keranjang.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function list(Request $request)
    {
        $user = Auth::user();
        $isApprover = $user && $user->role === 'approval';
        $isAdmin = $user && $user->role === 'atasan';

        if ($isApprover || $isAdmin) {
            // Approval/Atasan: Show all pengajuan
            $query = DB::table('pengajuan')
                ->leftJoin('users as u', 'pengajuan.user_id', '=', 'u.id')
                ->leftJoin('gudang as g', 'pengajuan.kode_gudang', '=', 'g.kode_gudang')
                ->select('pengajuan.*', 'u.nama as user_nama', 'g.nama_gudang');
            
            // Filter by gudang
            if ($request->filled('kode_gudang')) {
                $query->where('pengajuan.kode_gudang', $request->kode_gudang);
            }
            
            // Filter by dari tanggal
            if ($request->filled('dari_tanggal')) {
                $query->whereDate('pengajuan.tanggal', '>=', $request->dari_tanggal);
            }
            
            // Filter by sampai tanggal
            if ($request->filled('sampai_tanggal')) {
                $query->whereDate('pengajuan.tanggal', '<=', $request->sampai_tanggal);
            }
            
            $items = $query->orderBy('pengajuan.created_at', 'desc')->paginate(20);
            $view = 'content.pengajuan.list-approval';
        } else {
            // Regular user: Show only their own pengajuan
            $items = DB::table('pengajuan')
                ->leftJoin('gudang as g', 'pengajuan.kode_gudang', '=', 'g.kode_gudang')
                ->select('pengajuan.*', 'g.nama_gudang')
                ->where('pengajuan.user_id', Auth::id())
                ->orderBy('pengajuan.created_at', 'desc')
                ->paginate(20);
            $view = 'content.pengajuan.list';
        }

        // Get list gudang for filter dropdown
        $gudangs = DB::table('gudang')->select('kode_gudang', 'nama_gudang')->get();

        return view($view, compact('items', 'isApprover', 'gudangs'));
    }

    public function show($id)
    {
        $pengajuan = Pengajuan::find($id);
        
        if (!$pengajuan) {
            return back()->with('error', 'Pengajuan tidak ditemukan.');
        }

        // Authorization: User bisa lihat jika:
        // 1. Dia yang buat pengajuan (user_id sama)
        // 2. Dia adalah approval atau atasan
        $user = Auth::user();
        $isApprover = $user && $user->role === 'approval';
        $isAdmin = $user && $user->role === 'atasan';
        $isPengaju = $user && $pengajuan->user_id == $user->id;

        if (!$isApprover && !$isAdmin && !$isPengaju) {
            abort(403, 'Anda tidak memiliki akses ke pengajuan ini');
        }

        $details = DB::table('pengajuan_detail as pd')
            ->leftJoin('barang as b', 'pd.barang_id', '=', 'b.id')
            ->where('pd.pengajuan_id', $id)
            ->select('pd.*', 'b.nama_barang')
            ->get();

        // Get user info
        $user_pengaju = DB::table('users')->where('id', $pengajuan->user_id)->first();

        return view('content.pengajuan.show', compact('pengajuan', 'details', 'user_pengaju', 'isApprover', 'isPengaju'));
    }

    public function getDetails($id, Request $request)
    {
        $pengajuan = DB::table('pengajuan')->where('id', $id)->first();
        
        if (!$pengajuan) {
            return response()->json(['error' => 'Pengajuan tidak ditemukan'], 404);
        }

        // Authorization check
        $user = Auth::user();
        $isApprover = $user && $user->role === 'approval';
        $isAdmin = $user && $user->role === 'atasan';
        $isPengaju = $user && $pengajuan->user_id == $user->id;

        if (!$isApprover && !$isAdmin && !$isPengaju) {
            return response()->json(['error' => 'Akses ditolak'], 403);
        }

        $page = $request->query('page', 1);
        $perPage = 5;

        $detailsQuery = DB::table('pengajuan_detail as pd')
            ->leftJoin('barang as b', 'pd.barang_id', '=', 'b.id')
            ->where('pd.pengajuan_id', $id)
            ->select('pd.*', 'b.nama_barang');

        $totalItems = $detailsQuery->count();
        $details = $detailsQuery->paginate($perPage, ['*'], 'page', $page);

        $items = $details->getCollection()->map(function ($item) {
            return [
                'id' => $item->id,
                'nama_barang' => $item->nama_barang,
                'jumlah' => $item->jumlah,
                'jumlah_disetujui' => $item->jumlah_disetujui ?? 0,
                'status' => $item->status ?? null,
            ];
        });

        return response()->json([
            'items' => $items,
            'pengajuan_status' => $pengajuan->status,
            'pagination' => [
                'current_page' => $details->currentPage(),
                'last_page' => $details->lastPage(),
                'per_page' => $details->perPage(),
                'total' => $totalItems,
                'has_pages' => $totalItems > $perPage,
            ],
        ]);
    }

    public function approve(Request $request, $id)
    {
        if (!Auth::check() || Auth::user()->role !== 'approval') {
            abort(403);
        }

        $pengajuan = DB::table('pengajuan')->where('id', $id)->first();
        if (!$pengajuan) {
            return back()->with('error', 'Pengajuan tidak ditemukan.');
        }

        if ($pengajuan->status !== 'pending') {
            return back()->with('error', 'Pengajuan sudah diproses.');
        }

        // Get approval data from request (array of approved[detail_id] => qty)
        $approvedMap = $request->input('approved', []);
        $details = DB::table('pengajuan_detail')->where('pengajuan_id', $id)->get();

        DB::beginTransaction();
        try {
            $hasApproved = false;
            $hasRejected = false;
            $totalApproved = 0;
            $totalRejected = 0;

            // Validate stok availability for approved items
            foreach ($details as $d) {
                $approvedQty = isset($approvedMap[$d->id]) ? (int) $approvedMap[$d->id] : 0;
                
                if ($approvedQty > 0) {
                    $stokRow = DB::table('stok')
                        ->where('kode_gudang', $pengajuan->kode_gudang)
                        ->where('id_barang', $d->barang_id)
                        ->first();

                    if (!$stokRow || ($stokRow->stok ?? 0) < $approvedQty) {
                        throw new \Exception('Stok tidak cukup untuk barang ID: ' . $d->barang_id . ' (dibutuhkan: ' . $approvedQty . ', tersedia: ' . ($stokRow->stok ?? 0) . ')');
                    }
                }
            }

            // Process approval/rejection for each detail
            foreach ($details as $d) {
                $approvedQty = isset($approvedMap[$d->id]) ? (int) $approvedMap[$d->id] : 0;
                $approvedQty = min($approvedQty, $d->jumlah); // ensure not more than requested

                if ($approvedQty > 0) {
                    // Deduct stok for approved quantity
                    DB::table('stok')
                        ->where('kode_gudang', $pengajuan->kode_gudang)
                        ->where('id_barang', $d->barang_id)
                        ->decrement('stok', $approvedQty);

                    // Update detail: approved
                    DB::table('pengajuan_detail')
                        ->where('id', $d->id)
                        ->update([
                            'jumlah_disetujui' => $approvedQty,
                            'status' => 'approved',
                        ]);

                    $hasApproved = true;
                    $totalApproved += $approvedQty;
                } else {
                    // Update detail: rejected
                    DB::table('pengajuan_detail')
                        ->where('id', $d->id)
                        ->update([
                            'jumlah_disetujui' => 0,
                            'status' => 'rejected',
                        ]);

                    $hasRejected = true;
                    $totalRejected += $d->jumlah;
                }
            }

            // Update pengajuan status
            $status = 'approved';
            if ($hasApproved && $hasRejected) {
                $status = 'partial_approved';
            } elseif (!$hasApproved && $hasRejected) {
                $status = 'rejected';
            }

            DB::table('pengajuan')->where('id', $id)->update([
                'status' => $status,
                'updated_at' => now(),
            ]);

            // Create barang_keluar entries for approved items
            if ($hasApproved) {
                $approvedDetails = DB::table('pengajuan_detail')
                    ->where('pengajuan_id', $id)
                    ->where('status', 'approved')
                    ->get();

                foreach ($approvedDetails as $detail) {
                    DB::table('barang_keluar')->insert([
                        'id_barang' => $detail->barang_id,
                        'kode_gudang' => $pengajuan->kode_gudang,
                        'jumlah' => $detail->jumlah_disetujui,
                        'tanggal' => Carbon::now()->toDateString(),
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
            }

            DB::commit();

            // Send notification to pengaju
            $pengajuan = Pengajuan::find($id);
            NotificationHelper::notifyApprovalDecision($pengajuan, true, $totalApproved, $totalRejected);

            $statusMsg = $status === 'partial_approved' 
                ? "Pengajuan disetujui sebagian: $totalApproved disetujui, $totalRejected ditolak."
                : ($status === 'rejected' 
                    ? "Pengajuan ditolak seluruhnya."
                    : "Pengajuan disetujui seluruhnya.");

            return redirect()->route('pengajuan.show', $id)->with('success', $statusMsg);
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal memproses approval: ' . $e->getMessage());
        }
    }

    public function reject(Request $request, $id)
    {
        if (!Auth::check() || Auth::user()->role !== 'approval') {
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
            'rejection_reason' => $request->input('note') ?? null,
        ]);

        // Send notification to pengaju
        NotificationHelper::notifyApprovalDecision($pengajuan, false);

        return redirect()->route('pengajuan.show', $id)->with('success', 'Pengajuan ditolak.');
    }
}
