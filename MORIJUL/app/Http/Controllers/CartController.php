<?php

namespace App\Http\Controllers;

use App\Models\Product; // Pastikan model Product sudah ada
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session; // Mengimpor Session facade

class CartController extends Controller
{
    // Fungsi untuk menambah produk ke cart
    public function addToCart(Request $request, $id)
    {
        // Ambil produk berdasarkan ID
        $product = Product::find($id);

        // Cek apakah produk ada
        if (!$product) {
            return redirect()->back()->with('error', 'Product not found.');
        }

        // Ambil cart dari session atau buat cart baru jika belum ada
        $cart = Session::get('cart', []);

        // Jika produk sudah ada di cart, tambahkan quantity
        if (isset($cart[$id])) {
            $cart[$id]['quantity']++;
        } else {
            // Jika produk belum ada, tambahkan produk baru ke cart
            $cart[$id] = [
                "id" => $product->id,
                "name" => $product->name,
                "quantity" => 1,
                "price" => $product->price,
                "image" => $product->image,
            ];
        }

        // Simpan cart ke session
        Session::put('cart', $cart);

        // Redirect kembali dengan pesan sukses
        return redirect()->back()->with('success', 'Product added to cart successfully!');
    }

    // Menampilkan halaman cart
    public function index()
    {
        $cartItems = Session::get('cart', []); // Ambil item dari session cart
        $subtotal = collect($cartItems)->sum(function ($item) {
            return $item['price'] * $item['quantity'];
        });

        return view('cart.index', compact('cartItems', 'subtotal'));
    }

    // Proses ke halaman checkout
    public function checkout()
    {
        // Ambil data cart dari session
        $cartItems = session()->get('cart', []);
        $subtotal = collect($cartItems)->sum(function ($item) {
            return $item['price'] * $item['quantity'];
        });

        // Ambil informasi stok produk
        foreach ($cartItems as &$item) {
            $product = Product::find($item['id']);
            $item['stock'] = $product ? $product->stock : 0;
        }

        // Kirim data cart, subtotal, dan stok ke view checkout
        return view('checkout', compact('cartItems', 'subtotal'));
    }

    // Hapus produk dari cart
    public function removeCart($id)
    {
        // Ambil cart dari session
        $cart = session()->get('cart', []);

        // Cek jika produk ada di cart
        if (isset($cart[$id])) {
            unset($cart[$id]); // Hapus produk dari cart
            session()->put('cart', $cart); // Simpan kembali cart ke session
        }

        return redirect()->back()->with('success', 'Product removed from cart successfully!');
    }
}
