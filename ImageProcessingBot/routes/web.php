<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ImageController;

// Use 'image.index' and 'image.process' for consistency
Route::get('/', [ImageController::class, 'index'])->name('imago.index');
Route::post('/process-image', [ImageController::class, 'process'])->name('imago.process');
