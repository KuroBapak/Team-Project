<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class DeliveryController extends Controller
{
    public function dashboard(){
        $orders = Order::where('payment_status','pending')->with('items.product')->get();
        return view('admin.products.delivery', compact('orders'));
    }

}
