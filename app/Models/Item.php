<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Order;

class Item extends Model
{
    use HasFactory;
    protected $table = 'item'; 
    protected $primaryKey = 'item_id';
    public $fillable = ['description', 'sell_price', 'cost_price', 'image_path'];
    
    public function orders()
    {
        return $this->belongsToMany(Order::class, 'orderline', 'item_id', 'orderinfo_id')->withPivot('quantity');
    }

    public function stock()
    {
        return $this->hasOne(Stock::class,'item_id');
    }
}
