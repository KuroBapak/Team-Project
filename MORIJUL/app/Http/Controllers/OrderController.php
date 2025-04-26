<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Session;

class OrderController extends Controller
{
    public function store(Request $request)
    {
        // Validasi input
        $request->validate([
            'buyer_name'   => 'required|string|max:255',
            'room_number'  => 'required|string|max:50',
            'payment_type' => 'required|string|in:COD,QRIS',
        ]);

        // Ambil data cart dari session
        $cartItems = session()->get('cart', []);

        // Cek apakah cart kosong
        if (empty($cartItems)) {
            return redirect()->back()->with('error', 'Cart is empty.');
        }

        // 1️⃣ Periksa stok tiap item sebelum membuat order
        foreach ($cartItems as $item) {
            $product = Product::find($item['id']);
            if (! $product) {
                return redirect()->back()->with('error', "Product #{$item['id']} not found.");
            }
            if ($item['quantity'] > $product->stock) {
                return redirect()->back()
                    ->with('error', "Sorry, only {$product->stock} of “{$product->name}” left in stock. You requested {$item['quantity']}.");
            }
        }

        // 2️⃣ Semua OK, generate kode verifikasi & buat order
        $verificationCode = strtoupper(Str::random(8));

        $order = Order::create([
            'buyer_name'       => $request->buyer_name,
            'room_number'      => $request->room_number,
            'payment_type'     => $request->payment_type,
            'payment_status'   => 'pending',
            'total_amount'     => collect($cartItems)->sum(fn($i) => $i['price'] * $i['quantity']),
            'verification_code'=> $verificationCode,
        ]);

        // 3️⃣ Simpan order items & kurangi stok
        foreach ($cartItems as $item) {
            $order->items()->create([
                'product_id' => $item['id'],
                'quantity'   => $item['quantity'],
                'price'      => $item['price'],
            ]);

            $product = Product::find($item['id']);
            $product->decrement('stock', $item['quantity']);
        }

        // Kosongkan cart dan redirect ke halaman sukses
        session()->forget('cart');

        return redirect()->route('order.success', $order->id);
    }

    public function success($id)
    {
        $order      = Order::with('items')->findOrFail($id);
        $totalItems = $order->items->sum('quantity');
        $code       = $order->verification_code;

        return view('code', compact('order', 'totalItems', 'code'));
    }
}
