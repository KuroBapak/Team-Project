<?php

namespace App\Http\Controllers;

use App\Models\ProcessedImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class HistoryController extends Controller
{
    // Show the history page
// app/Http/Controllers/HistoryController.php
public function index()
{
    // Get all images for the user; JavaScript will filter them.
    $images = Auth::user()->processedImages()->latest()->get();
    return view('history', ['images' => $images]);
}

    // Handle the download request
// app/Http/Controllers/HistoryController.php
// ... other methods are correct ...

    // Handle the download request
    public function download(ProcessedImage $image)
    {
        // Security: Ensure the logged-in user owns this image
        if ($image->user_id !== Auth::id()) {
            abort(403);
        }

        // FIX: Use the response() helper for a clean, type-hinted download.
        $filePath = Storage::disk('public')->path($image->processed_path);

        return response()->download($filePath);
    }

// ... other methods are correct ...

    // Handle the delete request
    public function destroy(ProcessedImage $image)
    {
        // Security: Ensure the logged-in user owns this image
        if ($image->user_id !== Auth::id()) {
            abort(403);
        }

        // Delete the physical files from storage
        Storage::disk('public')->delete($image->original_path);
        Storage::disk('public')->delete($image->processed_path);

        // Delete the record from the database
        $image->delete();

        return redirect()->route('history.index')->with('success', 'Image history deleted successfully.');
    }
}
