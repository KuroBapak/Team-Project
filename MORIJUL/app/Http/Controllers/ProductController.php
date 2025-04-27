<?php

namespace App\Http\Controllers;

use App\Models\Chats;
use Illuminate\Http\Request;
use App\Models\Product;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    // Menampilkan halaman dashboard admin dengan daftar produk
    public function index()
    {
        $products = Product::all();
        return view('admin.products.index', compact('products'));
    }

    public function guest(Request $request)
    {
        $products = Product::all();
            // 1. Pull the chat code (if the user has already entered one)
    $code = $request->session()->get('chat_code');

    // 2. If we have a code, load its messages; otherwise an empty collection
    $chats = $code
        ? Chats::where('unique_code', $code)
               ->orderBy('created_at')
               ->get()
        : collect();

    // 3. Pass both into the Blade
    return view('products.index', compact('products','code','chats'));
    }

    // Menyimpan produk baru
    public function store(Request $request)
    {
        // Validasi input
        $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric',
            'size' => 'required|string|in:kecil,sedang,besar',
            'stock' => 'required|integer|min:0',
            'image' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        // Upload gambar
        $imagePath = $request->file('image')->store('images', 'public');

        // Simpan produk baru
        Product::create([
            'name' => $request->name,
            'price' => $request->price,
            'size' => $request->size,
            'stock' => $request->stock,
            'image' => $imagePath,
        ]);

        return redirect()->route('admin.products')->with('status', 'Product added successfully!');
    }

    // Menampilkan form edit produk
    public function edit($id)
    {
        $product = Product::findOrFail($id);
        return view('admin.edit', compact('product'));
    }

    // Memperbarui produk
    public function update(Request $request, $id)
    {
        // Validasi input
        $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric',
            'size' => 'required|string|in:kecil,sedang,besar',
            'stock' => 'required|integer|min:0',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $product = Product::findOrFail($id);

        // Jika ada gambar yang diunggah, hapus gambar lama dan simpan yang baru
        if ($request->hasFile('image')) {
            Storage::delete('public/' . $product->image);
            $imagePath = $request->file('image')->store('images', 'public');
            $product->image = $imagePath;
        }

        // Perbarui data produk
        $product->update([
            'name' => $request->name,
            'price' => $request->price,
            'size' => $request->size,
            'stock' => $request->stock,
        ]);

        return redirect()->route('admin.products')->with('status', 'Product updated successfully!');
    }

    // Menghapus produk
    public function destroy($id)
    {
        $product = Product::findOrFail($id);
        Storage::delete('public/' . $product->image);
        $product->delete();
        return redirect()->route('admin.products')->with('status', 'Product deleted successfully!');
    }
}
