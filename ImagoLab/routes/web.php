<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ImageController;
use App\Http\Controllers\HistoryController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ToolController; // <-- Add this

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

//## ENTRY & SELECTION ROUTES ##//
Route::get('/', [ToolController::class, 'selection'])->name('selection');
Route::post('/select-tool', [ToolController::class, 'storeSelection'])->name('tool.select');

//## EDITOR & PROCESSING ROUTES ##//
Route::get('/editor', [ImageController::class, 'index'])->name('editor');
Route::post('/process-image', [ImageController::class, 'process'])->name('imago.process');

//## AUTHENTICATED ROUTES (Require Login) ##//
Route::middleware('auth')->group(function () {
    // The dashboard now also leads to the tool selection.
    Route::get('/dashboard', [ToolController::class, 'selection'])->name('dashboard');

    // History and Profile routes remain the same.
    Route::get('/history', [HistoryController::class, 'index'])->name('history.index');
    Route::get('/history/{image}/download', [HistoryController::class, 'download'])->name('history.download');
    Route::delete('/history/{image}', [HistoryController::class, 'destroy'])->name('history.destroy');
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

//## BREEZE AUTHENTICATION ROUTES ##//
require __DIR__.'/auth.php';
