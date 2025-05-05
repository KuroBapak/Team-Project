<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = ['buyer_name', 'room_number', 'payment_type', 'payment_status', 'total_amount', 'order_code'];

    // Relasi dengan OrderItem
    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function chats()
    {
        return $this->hasMany(Chats::class, 'order_code', 'order_code');
    }
}
