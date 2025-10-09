<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ImageController;
use App\Http\Controllers\HistoryController;
use App\Http\Controllers\ProfileController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

//## PUBLIC ROUTES (For Guests) ##//

// The main image processing page for guests.
Route::get('/', [ImageController::class, 'index'])->name('imago.index');
Route::post('/process-image', [ImageController::class, 'process'])->name('imago.process');


//## AUTHENTICATED ROUTES (Require Login) ##//

Route::middleware('auth')->group(function () {
    // THE FIX: The dashboard is now handled by the ImageController.
    Route::get('/dashboard', [ImageController::class, 'index'])->name('dashboard');

    // Image History Routes
    Route::get('/history', [HistoryController::class, 'index'])->name('history.index');
    Route::get('/history/{image}/download', [HistoryController::class, 'download'])->name('history.download');
    Route::delete('/history/{image}', [HistoryController::class, 'destroy'])->name('history.destroy');

    // User Profile Routes (from Breeze)
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});


//## BREEZE AUTHENTICATION ROUTES ##//

require __DIR__.'/auth.php';
