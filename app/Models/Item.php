<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class Item extends Model {
    use SoftDeletes;
    protected $fillable = ['category_id', 'item_code', 'name', 'stock', 'location'];
    public function category() { 
        return $this->belongsTo(Category::class); 
    }
    public function getStatusAttribute() {
        return $this->stock <= 0 ? 'Habis' : 'Tersedia';
    }
}