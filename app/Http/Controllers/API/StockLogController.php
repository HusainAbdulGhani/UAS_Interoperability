<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\StockLog;
use Illuminate\Http\Request;

class StockLogController extends Controller
{
    public function index() {
        $logs = StockLog::with('item')->latest()->get();
        return response()->json(['status' => 'success', 'data' => $logs]);
    }
}
