<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Chats extends Model
{
    use HasFactory;

    protected $fillable = ['order_code','sender','message'];

    // If you want eager-loading:
    public function order()
    {
        return $this->belongsTo(Order::class, 'order_code', 'order_code');
    }
}
