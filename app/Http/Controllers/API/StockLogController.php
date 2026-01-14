<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\StockLog;

class StockLogController extends Controller
{
    public function index()
    {
        // Mengambil log terbaru beserta data barangnya
        $logs = StockLog::with('item')->latest()->get();
        return response()->json(['status' => 'success', 'data' => $logs]);
    }
}