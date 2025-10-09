<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Models\ProcessedImage;
use Illuminate\View\View;

class ImageController extends Controller
{
    /**
     * Display the correct view based on login status.
     * Guests see 'imago', logged-in users see 'dashboard'.
     */
    public function index(): View
    {
        // If the user is logged in, show the dashboard version.
        if (Auth::check()) {
            return view('dashboard');
        }

        // Otherwise, show the guest version.
        return view('imago');
    }

    /**
     * Process the image and return to the correct view
     * with the results.
     */
    public function process(Request $request)
    {
        // ... all of your existing validation and processing logic is perfect ...
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,webp|max:10240',
            'mode' => 'required|in:removebg,grayscale',
        ]);
        $file = $request->file('image');
        $mode = $request->input('mode');
        $originalFilename = time() . '_' . $file->getClientOriginalName();
        $originalPath = $file->storeAs('originals', $originalFilename, 'public');
        $fastapiUrl = 'http://127.0.0.1:8001/process-image';
        $response = Http::attach(
            'file', file_get_contents($file), $file->getClientOriginalName()
        )->post($fastapiUrl, ['mode' => $mode,]);

        if (!$response->successful() || empty($response->json()['url'])) {
            return back()->with('error', 'AI service failed or returned an invalid response.');
        }

        $data = $response->json();
        $processedPath = $data['url'];

        if (Auth::check()) {
            ProcessedImage::create([
                'user_id' => Auth::id(),
                'original_path' => $originalPath,
                'processed_path' => $processedPath,
            ]);
        }
        // ---- END OF EXISTING LOGIC ----


        // NEW: Determine which view to send the user back to.
        $viewName = Auth::check() ? 'dashboard' : 'imago';

        return view($viewName, [
            'originalUrl' => Storage::url($originalPath),
            'processedUrl' => Storage::url($processedPath)
        ]);
    }
}
