<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Support\Facades\Session;

class OrderController extends Controller
{
    public function store(Request $request)
    {
        // Validasi input
        $request->validate([
            'buyer_name' => 'required|string|max:255',
            'room_number' => 'required|string|max:50',
            'payment_type' => 'required|string|in:COD,QRIS',
        ]);

        // Ambil data cart dari session
        $cartItems = session()->get('cart', []);

        // Cek apakah cart kosong
        if (empty($cartItems)) {
            return redirect()->back()->with('error', 'Cart is empty.');
        }

        // Buat pesanan baru
        $order = Order::create([
            'buyer_name' => $request->input('buyer_name'),
            'room_number' => $request->input('room_number'),
            'payment_type' => $request->input('payment_type'),
            'payment_status' => 'pending', // Menggunakan status yang valid
            'total_amount' => collect($cartItems)->sum(function ($item) {
                return $item['price'] * $item['quantity'];
            }),
        ]);

        foreach ($cartItems as $item) {
            $orderItem = $order->items()->create([
                'product_id' => $item['id'],
                'quantity' => $item['quantity'],
                'price' => $item['price'],
            ]);

            // Kurangi stok produk
            $product = Product::find($item['id']);
            if ($product) {
                $product->stock -= $item['quantity'];
                $product->save();
            }
        }

        // Kosongkan cart
        session()->forget('cart');

        // Redirect dengan pesan sukses
        return redirect()->route('home')->with('success', 'Order placed successfully!');
    }

}
