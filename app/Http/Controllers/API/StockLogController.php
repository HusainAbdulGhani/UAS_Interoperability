<?php
namespace App\Http\Controllers\API;
use App\Http\Controllers\Controller;
use App\Models\StockLog;
use Illuminate\Http\Request;
class StockLogController extends Controller {
    public function index(Request $request) {
        $query = StockLog::with('item.category');

        if ($request->has('item_id')) {
            $query->where('item_id', $request->input('item_id'));
        }
        return response()->json([
            'status' => 'success', 
            'data' => $query->get()
        ]);
    }
}