<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Item extends Model
{
    protected $fillable = ['category_id', 'item_code', 'name', 'stock', 'location'];

public function category() {
    return $this->belongsTo(Category::class);
}
}
