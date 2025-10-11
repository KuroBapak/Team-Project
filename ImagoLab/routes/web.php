<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ImageController;
use App\Http\Controllers\HistoryController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ToolController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

//## ENTRY & DASHBOARD ROUTE ##//
Route::get('/', [DashboardController::class, 'index'])->name('home');

//## TOOL SELECTION & PROCESSING ##//
Route::get('/selection', [ToolController::class, 'selection'])->name('selection');
Route::post('/select-tool', [ToolController::class, 'storeSelection'])->name('tool.select');
Route::get('/editor', [ImageController::class, 'index'])->name('editor'); // The main editor URL
Route::post('/process-image', [ImageController::class, 'process'])->name('imago.process');

//## AUTHENTICATED ROUTES (Require Login) ##//
Route::middleware('auth')->group(function () {
    // A logged-in user's dashboard is the same as the home page
    Route::get('/dashboard', [ToolController::class, 'selection'])->name('dashboard');

    // History and Profile routes
    Route::get('/history', [HistoryController::class, 'index'])->name('history.index');
    Route::get('/history/{image}/download', [HistoryController::class, 'download'])->name('history.download');
    Route::delete('/history/{image}', [HistoryController::class, 'destroy'])->name('history.destroy');
    Route::delete('/history', [HistoryController::class, 'clearAll'])->name('history.clearAll');
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

//## BREEZE AUTHENTICATION ROUTES ##//
require __DIR__.'/auth.php';
