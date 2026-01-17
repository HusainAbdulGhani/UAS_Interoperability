<?php
namespace App\Http\Controllers\API;
use App\Http\Controllers\Controller;
use App\Models\Item;
use App\Models\StockLog;
use Illuminate\Http\Request;

class ItemController extends Controller {
    public function index() {
        return response()->json(['status' => 'success', 'data' => Item::with('category')->get()]);
    }

    public function store(Request $request) {
        $v = $this->validate($request, [
            'category_id' => 'required|exists:categories,id',
            'item_code' => 'required|unique:items',
            'name' => 'required',
            'stock' => 'required|integer|min:0',
            'location' => 'required'
        ]);
        $item = Item::create($v);
        StockLog::create(['item_id' => $item->id, 'type' => 'in', 'amount' => $item->stock, 'description' => 'Stok awal']);
        return response()->json(['status' => 'success', 'data' => $item], 201);
    }

    public function show($id) {
        $item = Item::with('category')->find($id);
        return $item ? response()->json(['status' => 'success', 'data' => $item]) : response()->json(['message' => 'Not Found'], 404);
    }

    public function update(Request $request, $id) {
        $item = Item::find($id);
        if (!$item) return response()->json(['message' => 'Not Found'], 404);
        $v = $this->validate($request, [
            'category_id' => 'exists:categories,id',
            'item_code' => 'unique:items,item_code,' . $id,
            'stock' => 'integer|min:0',
        ]);
        $oldStock = $item->stock;
        $item->update($v);
        if (isset($v['stock']) && $oldStock != $item->stock) {
            $diff = $item->stock - $oldStock;
            StockLog::create(['item_id' => $item->id, 'type' => $diff > 0 ? 'in' : 'out', 'amount' => abs($diff), 'description' => 'Update stok']);
        }
        return response()->json(['status' => 'success', 'data' => $item]);
    }

    public function destroy($id)
    {
        $item = Item::find($id);

        if (!$item) {
            return response()->json(['message' => 'Barang tidak ditemukan'], 404);
        }
        \App\Models\StockLog::create([
            'item_id' => $item->id,
            'type' => 'out',
            'amount' => $item->stock, 
            'description' => 'BARANG DIHAPUS: STOK HABIS & KOSONG'
        ]);
        $item->update(['stock' => 0]);
        $item->delete();
    
        return response()->json(['message' => 'Barang berhasil dihapus, stok tercatat kosong.'], 200);
    }
}