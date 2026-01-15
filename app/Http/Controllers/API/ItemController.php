<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Item;
use App\Models\StockLog;
use Illuminate\Http\Request;

class ItemController extends Controller
{
    /**
     * Menampilkan semua data barang beserta kategorinya.
     */
    public function index() {
        $items = Item::with('category')->get();
        return response()->json([
            'status' => 'success',
            'data' => $items
        ]);
    }

    /**
     * Menyimpan barang baru dan mencatat log stok awal.
     */
    public function store(Request $request) {
        $validated = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'item_code' => 'required|unique:items',
            'name' => 'required',
            'stock' => 'required|integer|min:0',
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
    
        return response()->json([
            'status' => 'success',
            'message' => 'Barang berhasil ditambahkan',
            'data' => $item
        ], 201);
    }

    /**
     * Menampilkan detail satu barang.
     */
    public function show($id) {
        $item = Item::with('category')->find($id);
        if (!$item) {
            return response()->json([
                'status' => 'error',
                'message' => 'Barang dengan ID ' . $id . ' tidak ditemukan.'
            ], 404);
        }
        return response()->json([
            'status' => 'success', 
            'data' => $item
        ]);
    }

    /**
     * Memperbarui data barang dan mencatat log jika stok berubah.
     */
    public function update(Request $request, string $id)
    {
        $item = Item::find($id);

        if (!$item) {
            return response()->json([
                'status' => 'error', 
                'message' => 'Barang tidak ditemukan'
            ], 404);
        }

        $validated = $request->validate([
            'category_id' => 'exists:categories,id',
            'item_code' => 'unique:items,item_code,' . $id,
            'name' => 'string',
            'stock' => 'integer|min:0',
            'location' => 'string'
        ]);

        // Hitung selisih stok sebelum update dilakukan
        $oldStock = $item->stock;
        
        $item->update($validated);

        // LOGIKA RIWAYAT STOK: Jika nilai stok berubah, buat catatan log otomatis
        if (isset($validated['stock']) && $oldStock != $item->stock) {
            $diff = $item->stock - $oldStock;
            StockLog::create([
                'item_id' => $item->id,
                'type' => $diff > 0 ? 'in' : 'out', // 'in' jika bertambah, 'out' jika berkurang
                'amount' => abs($diff),
                'description' => 'Perubahan stok melalui update data barang'
            ]);
        }

        return response()->json([
            'status' => 'success', 
            'message' => 'Barang berhasil diperbarui', 
            'data' => $item
        ]);
    }

    /**
     * Menghapus barang dari database.
     */
    public function destroy(string $id)
    {
        $item = Item::find($id);

        if (!$item) {
            return response()->json([
                'status' => 'error', 
                'message' => 'Barang tidak ditemukan'
            ], 404);
        }

        $item->delete();

        return response()->json([
            'status' => 'success', 
            'message' => 'Barang berhasil dihapus'
        ]);
    }
}