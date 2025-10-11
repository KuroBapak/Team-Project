<?php

namespace App\Http\Controllers;

// --- MISSING IMPORTS ADDED HERE ---
use App\Models\ProcessedImage;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
// --- END OF IMPORTS ---

class ImageController extends Controller
{
    /**
     * Display the correct view based on login status.
     * Guests see 'imago', logged-in users see 'dashboard'.
     */
    public function index(): View
    {
        $toolType = session('tool_type', 'basic');
        $viewName = Auth::check() ? 'user' : 'guest';
        return view($viewName, ['toolType' => $toolType]);
    }

    /**
     * Process the image and return to the correct view with the results.
     */
    public function process(Request $request)
    {
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,webp|max:10240',
            'mode' => 'required|in:removebg,grayscale,superres',
        ]);

        $file = $request->file('image');
        $mode = $request->input('mode');

        $originalFilename = time() . '_' . $file->getClientOriginalName();
        $originalPath = $file->storeAs('originals', $originalFilename, 'public');

        $fastapiUrl = 'http://127.0.0.1:8001/process-image';

        // --- INCOMPLETE CODE IS NOW COMPLETE ---
        try {
            $response = Http::timeout(180)->attach(
                'file', file_get_contents($file), $file->getClientOriginalName()
            )->post($fastapiUrl, ['mode' => $mode,]);

            if (!$response->successful() || empty($response->json()['url'])) {
                return back()->with('error', 'AI service failed or returned an invalid response.');
            }

        } catch (ConnectionException $e) {
            return back()->with('error', 'The AI processing service is currently unavailable. Please try again later.');
        }
        // --- END OF COMPLETED CODE ---

        $data = $response->json();
        $processedPath = $data['url'];
        $toolType = session('tool_type', 'basic');

        if (Auth::check()) {
            ProcessedImage::create([
                'user_id' => Auth::id(),
                'tool_type' => $toolType,
                'original_path' => $originalPath,
                'processed_path' => $processedPath,
            ]);
        }

        $viewName = Auth::check() ? 'user' : 'guest';

        return view($viewName, [
            'toolType' => $toolType,
            'originalUrl' => Storage::url($originalPath),
            'processedUrl' => Storage::url($processedPath)
        ]);
    }
}
