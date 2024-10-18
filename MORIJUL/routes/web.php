<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\OrderController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// 1. Home Page
Route::get('/', function () {
    return view('home'); // Home page
})->name('home');

Route::get('/products', [ProductController::class, 'guest'])->name('products');
// Route untuk menampilkan isi keranjang
Route::get('/cart', [CartController::class, 'index'])->name('cart.index');

// Route untuk menambah item ke keranjang
Route::post('/cart/add/{id}', [CartController::class, 'addToCart'])->name('cart.add');

// Route untuk menghapus item dari keranjang
Route::post('/cart/remove/{id}', [CartController::class, 'removeCart'])->name('cart.remove');



Route::post('/order/store', [OrderController::class, 'store'])->name('order.store');




Route::get('/login', [AdminController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AdminController::class, 'login']);
Route::post('/logout', [AdminController::class, 'logout'])->name('logout');


// Route untuk halaman dashboard admin (dilindungi oleh middleware admin)
Route::middleware('admin')->group(function () {
    Route::get('/admin/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
    Route::get('/admin/products', [ProductController::class, 'index'])->name('admin.products');
    Route::post('/admin/products', [ProductController::class, 'store'])->name('admin.products.store');
    Route::get('/admin/products/edit/{id}', [ProductController::class, 'edit'])->name('admin.products.edit');
    Route::put('/admin/products/{id}', [ProductController::class, 'update'])->name('admin.products.update');
    Route::delete('/admin/products/{id}', [ProductController::class, 'destroy'])->name('admin.products.delete');
    Route::put('/admin/orders/{id}/update-status', [AdminController::class, 'updatePaymentStatus'])->name('admin.updatePaymentStatus');
    Route::delete('/admin/orders/{id}', [AdminController::class, 'deleteOrder'])->name('admin.deleteOrder');
});



// Route untuk halaman checkout
Route::get('/checkout', [CartController::class, 'checkout'])->name('checkout');

// Route untuk menyimpan pesanan
Route::post('/order/store', [OrderController::class, 'store'])->name('order.store');


