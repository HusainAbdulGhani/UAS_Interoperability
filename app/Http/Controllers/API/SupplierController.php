<?php
namespace App\Http\Controllers\API;
use App\Http\Controllers\Controller;
use App\Models\Supplier;
use Illuminate\Http\Request;

class SupplierController extends Controller {
    /**
     * Get all suppliers
     */
    public function index() {
        $suppliers = Supplier::all();
        return response()->json(['status' => 'success', 'data' => $suppliers]);
    }

    /**
     * Create a new supplier
     */
    public function store(Request $request) {
        $v = $this->validate($request, [
            'nama_supplier' => 'required|string|max:255',
            'perusahaan' => 'required|string|max:255'
        ]);
        
        $supplier = Supplier::create($v);
        return response()->json(['status' => 'success', 'data' => $supplier, 'message' => 'Supplier berhasil ditambahkan'], 201);
    }

    /**
     * Delete a supplier
     */
    public function destroy($id) {
        $supplier = Supplier::find($id);

        if (!$supplier) {
            return response()->json(['status' => 'error', 'message' => 'Supplier tidak ditemukan'], 404);
        }

        $supplier->delete();
    
        return response()->json([
            'status' => 'success',
            'message' => 'Supplier berhasil dihapus'
        ], 200);
    }
}

