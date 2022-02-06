<?php

namespace App\Models;

use App\Models\Cart;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Inventory extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_name', 'price', 'quantity', 'quantity_sold', 'quantity_remaining'
    ];

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function carts() {
        return $this->hasMany(Cart::class);
    }


}
