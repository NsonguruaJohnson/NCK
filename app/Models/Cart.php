<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Cart extends Model
{
    use HasFactory;

    protected $fillable = [
        'inventory_id',
        'quantity_bought',
        'user_id',
    ];

    public function cart() {
        return $this->belongsTo(User::class);
    }
}
