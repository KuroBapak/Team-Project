<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;

class ProductController extends Controller
{
    public function index() {
        $products = Product::all();
        return view('products.index', compact('products'));
    }

    public function store(Request $request) {
        $product = new Product();
        $product->name = $request->name;
        $product->price = $request->price;
        $product->size = $request->size;
        $product->image = $request->file('image')->store('images');
        $product->save();

        return redirect()->route('admin.products');
    }

    public function destroy($id) {
        Product::destroy($id);
        return redirect()->route('admin.products');
    }

}
