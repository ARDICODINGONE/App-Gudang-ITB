<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Supplier;

class SupplierController extends Controller
{
    public function index()
    {
        $suppliers = Supplier::orderBy('nama_supplier')->get();
        return view('content.supplier.index', compact('suppliers'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_supplier' => 'required|string|max:255|unique:supplier,nama_supplier',
            'alamat' => 'required|string|max:500',
            'no_telp' => 'required|string|max:50|unique:supplier,no_telp',
        ], [
            'nama_supplier.unique' => 'Nama supplier sudah ada, gunakan nama lain.',
            'no_telp.unique' => 'Nomor telepon sudah terdaftar.',
        ]);

        $new = Supplier::create([
            'nama_supplier' => $request->nama_supplier,
            'alamat' => $request->alamat,
            'no_telp' => $request->no_telp,
        ]);

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json(['id' => $new->id, 'nama_supplier' => $new->nama_supplier]);
        }

        return redirect()->route('supplier-index')->with('success', 'Supplier berhasil ditambahkan!');
    }

    public function update(Request $request, supplier $supplier)
    {
        $request->validate([
            'nama_supplier' => 'required|string|max:255|unique:supplier,nama_supplier,' . $supplier->id,
            'alamat' => 'required|string|max:500',
            'no_telp' => 'required|string|max:50|unique:supplier,no_telp,' . $supplier->id,
        ], [
            'nama_supplier.unique' => 'Nama supplier sudah ada, gunakan nama lain.',
            'no_telp.unique' => 'Nomor telepon sudah terdaftar.',
        ]);

        $supplier->update([
            'nama_supplier' => $request->nama_supplier,
            'alamat' => $request->alamat,
            'no_telp' => $request->no_telp,
        ]);

        return redirect()->route('supplier-index')->with('success', 'Data supplier berhasil diperbarui!');
    }

    public function destroy(supplier $supplier)
    {
        $supplier->delete();
        return redirect()->route('supplier-index')->with('success', 'Data supplier berhasil dihapus!');
    }
}
