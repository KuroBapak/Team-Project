<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class CartController extends Controller
{
    // Fungsi untuk menambah produk ke cart
    public function addToCart(Request $request, $id)
    {
        $product = Product::find($id);
        if (! $product) {
            return redirect()->back()->with('error', 'Product not found.');
        }

        $cart = Session::get('cart', []);
        $currentQty = $cart[$id]['quantity'] ?? 0;

        // Blokir jika melebihi stok
        if ($currentQty + 1 > $product->stock) {
            return redirect()->back()
                ->with('error', "Cannot add more than {$product->stock} of “{$product->name}”.");
        }

        // Tambah ke cart
        if (isset($cart[$id])) {
            $cart[$id]['quantity']++;
        } else {
            $cart[$id] = [
                'id'       => $product->id,
                'name'     => $product->name,
                'quantity' => 1,
                'price'    => $product->price,
                'image'    => $product->image,
            ];
        }

        Session::put('cart', $cart);
        return redirect()->back()->with('success', 'Product added to cart successfully!');
    }

    // Menampilkan halaman cart
    public function index()
    {
        $cartItems = Session::get('cart', []);
        $subtotal  = collect($cartItems)->sum(fn($i) => $i['price'] * $i['quantity']);
        return view('cart.index', compact('cartItems', 'subtotal'));
    }

    // Proses ke halaman checkout
    public function checkout()
    {
        $cartItems = session()->get('cart', []);
        $subtotal  = collect($cartItems)->sum(fn($i) => $i['price'] * $i['quantity']);

        foreach ($cartItems as &$item) {
            $product      = Product::find($item['id']);
            $item['stock']= $product ? $product->stock : 0;
        }

        return view('checkout', compact('cartItems', 'subtotal'));
    }

    // Update quantity di cart
    public function updateCartQuantity(Request $request, $id, $action)
    {
        $cart = Session::get('cart', []);
        if (isset($cart[$id])) {
            $product = Product::find($id);
            $qty     = $cart[$id]['quantity'];

            if ($action === 'increase') {
                if ($qty + 1 > $product->stock) {
                    return redirect()->back()
                        ->with('error', "Cannot exceed stock of {$product->stock} for “{$product->name}”.");
                }
                $cart[$id]['quantity']++;
            }
            elseif ($action === 'decrease' && $qty > 1) {
                $cart[$id]['quantity']--;
            }

            Session::put('cart', $cart);
        }

        return redirect()->back()->with('success', 'Cart quantity updated successfully!');
    }

    // Hapus produk dari cart
    public function removeCart($id)
    {
        $cart = session()->get('cart', []);
        if (isset($cart[$id])) {
            unset($cart[$id]);
            session()->put('cart', $cart);
        }
        return redirect()->back()->with('success', 'Product removed from cart successfully!');
    }
}
