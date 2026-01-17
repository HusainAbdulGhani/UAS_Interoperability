<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class StockLog extends Model {
    protected $fillable = ['item_id', 'type', 'amount', 'description'];
    public function item(){
        return $this->belongsTo(Item::class, 'item_id')->withTrashed();
    }
}