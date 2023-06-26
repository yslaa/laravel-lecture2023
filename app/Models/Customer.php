<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Order;
use App\Models\User;

class Customer extends Model
{
    use HasFactory;
    protected $table = 'customer';
    protected $primaryKey = 'customer_id';
    public $timestamps = false;
    public $guarded = ['customer_id'];

    public function orders() {
        return $this->hasMany(Order::class, 'customer_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getSearchResult(): SearchResult
    {
       $url = route('customer.show', $this->customer_id);
    
        return new \Spatie\Searchable\SearchResult(
           $this,
           $this->lname. " ". $this->fname,
           $url
        );
    }
}
