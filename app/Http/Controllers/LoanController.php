<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Models\Cart;
use App\Models\Loan;
use App\Models\LoanItem;

class LoanController extends Controller
{
    // 1. FUNGSI KIRIM PENGAJUAN (User)
    // Dipanggil saat user klik "Kirim" di halaman keranjang
    public function store(Request $request)
    {
        DB::transaction(function () {
            $userId = auth()->id();
            $carts = Cart::where('user_id', $userId)->get();
            
            if ($carts->isEmpty()) return;

            // Buat Header
            $loan = Loan::create([
                'loan_number' => 'REQ-' . date('YmdHis'),
                'user_id' => $userId,
                'status' => 'pending',
            ]);

            // Pindah Data Keranjang ke Loan Items
            foreach ($carts as $cart) {
                LoanItem::create([
                    'loan_id' => $loan->id,
                    'item_id' => $cart->item_id,
                    'warehouse_id' => $cart->warehouse_id,
                    'quantity_requested' => $cart->quantity,
                    'user_note' => $cart->user_note,
                    'quantity_approved' => null, // Default null sebelum diapprove
                ]);
            }

            // Hapus Keranjang
            Cart::where('user_id', $userId)->delete();
        });

        return redirect()->back()->with('success', 'Pengajuan berhasil dikirim.');
    }

    // 2. FUNGSI APPROVAL (Approver)
    // Dipanggil saat Approver klik "Approve" setelah edit jumlah
    public function approve(Request $request, $id)
    {
        // Validasi input array
        $request->validate([
            'items' => 'required|array',
            'items.*.qty' => 'required|integer|min:0',
            'items.*.note' => 'nullable|string',
        ]);

        DB::transaction(function () use ($request, $id) {
            $loan = Loan::findOrFail($id);

            // Loop setiap item yang diapprove
            foreach ($request->items as $itemId => $data) {
                LoanItem::where('id', $itemId)->where('loan_id', $loan->id)->update([
                    'quantity_approved' => $data['qty'], // Jumlah final keputusan approver
                    'approver_note' => $data['note'] ?? null
                ]);
            }

            // Update status utama
            $loan->update([
                'status' => 'approved',
                'approver_id' => auth()->id()
            ]);
        });

        return redirect()->back()->with('success', 'Pengajuan disetujui.');
    }
}