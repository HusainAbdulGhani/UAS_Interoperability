<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Item;
use App\Models\StockLog;
use Illuminate\Http\Request;

class ItemController extends Controller
{
    public function index() {
        // Mengambil data barang beserta nama kategorinya
        $items = Item::with('category')->get();
        return response()->json(['status' => 'success', 'data' => $items]);
    }

    public function store(Request $request) {
        $validated = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'item_code' => 'required|unique:items',
            'name' => 'required',
            'stock' => 'required|integer',
            'location' => 'required'
        ]);
    
        $item = Item::create($validated);
    
        // Otomatis catat log barang masuk pertama kali
        StockLog::create([
            'item_id' => $item->id,
            'type' => 'in',
            'amount' => $item->stock,
            'description' => 'Stok awal barang baru'
        ]);
    
        return response()->json(['status' => 'success', 'data' => $item], 201);
    }

    public function show(string $id)
    {
        $item = Item::with('category')->find($id);

        if (!$item) {
            return response()->json(['status' => 'error', 'message' => 'Barang tidak ditemukan'], 404);
        }

        return response()->json(['status' => 'success', 'data' => $item]);
    }

    public function update(Request $request, string $id)
    {
        $item = Item::find($id);

        if (!$item) {
            return response()->json(['status' => 'error', 'message' => 'Barang tidak ditemukan'], 404);
        }

        // Validasi data yang akan diupdate
        $validated = $request->validate([
            'category_id' => 'exists:categories,id',
            'item_code' => 'unique:items,item_code,' . $id,
            'name' => 'string',
            'stock' => 'integer',
            'location' => 'string'
        ]);

        $item->update($validated);

        return response()->json(['status' => 'success', 'message' => 'Barang berhasil diperbarui', 'data' => $item]);
    }

    public function destroy(string $id)
    {
        $item = Item::find($id);

        if (!$item) {
            return response()->json(['status' => 'error', 'message' => 'Barang tidak ditemukan'], 404);
        }

        $item->delete();

        return response()->json(['status' => 'success', 'message' => 'Barang berhasil dihapus']);
    }
}