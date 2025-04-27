<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Chats extends Model
{
    use HasFactory;

    protected $fillable = ['unique_code','sender','message'];

    // If you want eager-loading:
    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}
