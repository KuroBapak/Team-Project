<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\DeliveryController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\AdminChatController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Route::get('/', [ProductController::class, 'guest'])->name('products');
Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
Route::post('/cart/add/{id}', [CartController::class, 'addToCart'])->name('cart.add');
Route::post('/cart/remove/{id}', [CartController::class, 'removeCart'])->name('cart.remove');
Route::post('/cart/update/{id}/{action}', [CartController::class, 'updateCartQuantity'])->name('cart.update');

Route::get('/checkout', [CartController::class, 'checkout'])->name('checkout');
Route::post('/order/store', [OrderController::class, 'store'])->name('order.store');
Route::get('/order/success/{id}', [OrderController::class, 'success'])->name('order.success');

// Authentication
Route::get('/login', [AdminController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AdminController::class, 'login']);
Route::post('/logout', [AdminController::class, 'logout'])->name('logout');

/*
|--------------------------------------------------------------------------
| User Chat Routes (modal in guest view)
|--------------------------------------------------------------------------
|
| 1) Start chat by saving code to session
| 2) Send user messages (code pulled from session)
| 3) Optional JSON fetch
|
*/
Route::post('/chat/start', [ChatController::class, 'start'])->name('chat.start');
Route::post('/chat/send',  [ChatController::class, 'send'])->name('chat.send');
Route::get('/chat/fetch',  [ChatController::class, 'fetch'])->name('chat.fetch');
Route::post('/chat/reset',  [ChatController::class, 'reset'])->name('chat.reset');

/*
|--------------------------------------------------------------------------
| Protected: Delivery Dashboard
|--------------------------------------------------------------------------
*/
Route::middleware('delivery')->group(function(){
    Route::get('/delivery/dashboard', [DeliveryController::class, 'dashboard'])
         ->name('delivery.dashboard');
});

/*
|--------------------------------------------------------------------------
| Protected: Admin Dashboard & Chat
|--------------------------------------------------------------------------
*/
Route::middleware('admin')->prefix('admin')->group(function () {
    // Main dashboard
    Route::get('/dashboard',          [AdminController::class,     'dashboard'])->name('admin.dashboard');
    Route::get('/products',           [ProductController::class,   'index'])->name('admin.products');
    Route::post('/products',          [ProductController::class,   'store'])->name('admin.products.store');
    Route::get('/products/edit/{id}', [ProductController::class,   'edit'])->name('admin.products.edit');
    Route::put('/products/{id}',      [ProductController::class,   'update'])->name('admin.products.update');
    Route::delete('/products/{id}',   [ProductController::class,   'destroy'])->name('admin.products.delete');

    // Order actions
    Route::put('/orders/{id}/update-status', [AdminController::class, 'updatePaymentStatus'])
         ->name('admin.updatePaymentStatus');
    Route::delete('/orders/{id}',            [AdminController::class, 'deleteOrder'])
         ->name('admin.deleteOrder');

    // AJAX chat endpoints
    Route::get( '/chats/{code}/fetch', [AdminChatController::class, 'fetch'])->name('admin.chats.fetch');
    Route::post('/chats/{code}/send',  [AdminChatController::class, 'send'])->name('admin.chats.send');
});
