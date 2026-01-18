<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\barang;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    protected function findOrCreateCart(Request $request)
    {
        if (Auth::check()) {
            $cart = Cart::firstOrCreate(['user_id' => Auth::id()]);
        } else {
            $sessionId = $request->session()->getId();
            $cart = Cart::firstOrCreate(['session_id' => $sessionId]);
        }

        return $cart;
    }

    public function items(Request $request)
    {
        $cart = $this->findOrCreateCart($request);
        $items = $cart->items()->with('barang')->get()->map(function ($it) {
            return [
                'id' => $it->id,
                'barang_id' => $it->barang_id,
                'name' => $it->barang->nama_barang ?? null,
                'kode' => $it->barang->kode_barang ?? null,
                'price' => $it->price ?? $it->barang->harga ?? 0,
                'qty' => $it->quantity,
                'image' => $it->barang->image ? asset('storage/' . $it->barang->image) : asset('img/product-1.png'),
            ];
        });

        return response()->json([
            'items' => $items,
            'note' => $cart->note ?? null,
        ]);
    }

    public function updateNote(Request $request)
    {
        $data = $request->validate([
            'note' => 'nullable|string|max:1000',
        ]);

        $cart = $this->findOrCreateCart($request);
        $cart->note = $data['note'] ?? null;
        $cart->save();

        return response()->json(['ok' => true]);
    }

    public function add(Request $request)
    {
        $data = $request->validate([
            'barang_id' => 'required|integer|exists:barang,id',
            'quantity' => 'sometimes|integer|min:1',
        ]);

        $cart = $this->findOrCreateCart($request);

        $item = $cart->items()->where('barang_id', $data['barang_id'])->first();
        if ($item) {
            $item->quantity += $data['quantity'] ?? 1;
            $item->save();
        } else {
            $barang = barang::find($data['barang_id']);
            $cart->items()->create([
                'barang_id' => $data['barang_id'],
                'quantity' => $data['quantity'] ?? 1,
                'price' => $barang->harga ?? 0,
            ]);
        }

        return response()->json(['ok' => true]);
    }

    public function updateItem(Request $request, $id)
    {
        $data = $request->validate([
            'quantity' => 'required|integer|min:1',
        ]);

        $item = CartItem::findOrFail($id);
        $item->quantity = $data['quantity'];
        $item->save();

        return response()->json(['ok' => true]);
    }

    public function removeItem(Request $request, $id)
    {
        $item = CartItem::findOrFail($id);
        $item->delete();
        return response()->json(['ok' => true]);
    }

    public function clear(Request $request)
    {
        $cart = $this->findOrCreateCart($request);
        $cart->items()->delete();
        return response()->json(['ok' => true]);
    }
}
