<?php
namespace App\Http\Controllers\API;
use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoriesController extends Controller {
    /**
     * Get all categories with their items count
     */
    public function index() {
        $categories = Category::withCount('items')->get();
        return response()->json(['status' => 'success', 'data' => $categories]);
    }

    /**
     * Create a new category
     */
    public function store(Request $request) {
        $v = $this->validate($request, [
            'name' => 'required|string|unique:categories,name|max:255'
        ]);
        
        $category = Category::create($v);
        return response()->json(['status' => 'success', 'data' => $category], 201);
    }

    /**
     * Get a single category with its items
     */
    public function show($id) {
        $category = Category::with('items')->find($id);
        
        if (!$category) {
            return response()->json(['status' => 'error', 'message' => 'Kategori tidak ditemukan'], 404);
        }
        
        return response()->json(['status' => 'success', 'data' => $category]);
    }

    /**
     * Update a category
     */
    public function update(Request $request, $id) {
        $category = Category::find($id);
        
        if (!$category) {
            return response()->json(['status' => 'error', 'message' => 'Kategori tidak ditemukan'], 404);
        }
        
        $v = $this->validate($request, [
            'name' => 'required|string|unique:categories,name,' . $id . '|max:255'
        ]);
        
        $category->update($v);
        return response()->json(['status' => 'success', 'data' => $category, 'message' => 'Kategori berhasil diupdate']);
    }

    /**
     * Delete a category
     */
    public function destroy($id) {
        $category = Category::find($id);

        if (!$category) {
            return response()->json(['status' => 'error', 'message' => 'Kategori tidak ditemukan'], 404);
        }

        // Check if category has items
        if ($category->items()->count() > 0) {
            return response()->json([
                'status' => 'error',
                'message' => 'Kategori tidak dapat dihapus karena masih memiliki barang. Hapus atau pindahkan barang terlebih dahulu.'
            ], 422);
        }

        $category->delete();
    
        return response()->json([
            'status' => 'success',
            'message' => 'Kategori berhasil dihapus'
        ], 200);
    }
}