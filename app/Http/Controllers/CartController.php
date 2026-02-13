<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Barang;
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
        $page = $request->query('page', 1);
        $perPage = 5;
        
        $cartItemsQuery = $cart->items()->with('barang.stok');
        $totalItems = $cartItemsQuery->count();
        
        // Get paginated items
        $cartItems = $cartItemsQuery->paginate($perPage, ['*'], 'page', $page);
        
        $items = $cartItems->getCollection()->map(function ($it) {
            // Calculate total stock for this barang across all gudang
            $totalStock = $it->barang->stok()->sum('stok') ?? 0;
            
            // Get list of gudang names where this barang is in stock
            $gudangList = $it->barang->stok()
                ->where('stok', '>', 0)
                ->with('gudang')
                ->get()
                ->map(function ($s) {
                    return $s->gudang->nama_gudang ?? null;
                })
                ->filter()
                ->toArray();

            return [
                'id' => $it->id,
                'barang_id' => $it->barang_id,
                'name' => $it->barang->nama_barang ?? null,
                'price' => $it->price ?? $it->barang->harga ?? 0,
                'qty' => $it->quantity,
                'max_qty' => $totalStock,
                'gudang_list' => implode(', ', $gudangList) ?: 'Tidak ada stok',
                'image' => $it->barang->image ? asset('storage/' . $it->barang->image) : asset('img/product-1.png'),
            ];
        });

        return response()->json([
            'items' => $items,
            'note' => $cart->note ?? null,
            'pagination' => [
                'current_page' => $cartItems->currentPage(),
                'last_page' => $cartItems->lastPage(),
                'per_page' => $cartItems->perPage(),
                'total' => $totalItems,
                'has_pages' => $totalItems > $perPage,
            ],
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
        $barang = Barang::find($data['barang_id']);
        
        // Get total available stock
        $totalStock = $barang->stok()->sum('stok') ?? 0;
        $requestedQty = $data['quantity'] ?? 1;

        $item = $cart->items()->where('barang_id', $data['barang_id'])->first();
        if ($item) {
            $newQty = min($item->quantity + $requestedQty, $totalStock);
            $item->quantity = $newQty;
            $item->save();
        } else {
            $newQty = min($requestedQty, $totalStock);
            $cart->items()->create([
                'barang_id' => $data['barang_id'],
                'quantity' => $newQty,
                'price' => $barang->harga ?? 0,
            ]);
        }

        return response()->json([
            'ok' => true,
            'max_qty' => $totalStock
        ]);
    }

    public function updateItem(Request $request, $id)
    {
        $data = $request->validate([
            'quantity' => 'required|integer|min:1',
        ]);

        $item = CartItem::findOrFail($id);
        
        // Get total available stock for this barang
        $totalStock = $item->barang->stok()->sum('stok') ?? 0;
        
        // Ensure quantity doesn't exceed available stock
        $quantity = min($data['quantity'], $totalStock);
        
        $item->quantity = $quantity;
        $item->save();

        return response()->json([
            'ok' => true,
            'quantity' => $quantity,
            'max_qty' => $totalStock
        ]);
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
