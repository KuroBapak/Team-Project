<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Models\ProcessedImage;
use Illuminate\View\View;
use Illuminate\Http\Client\ConnectionException; // Import this class

class ImageController extends Controller
{
    public function index(): View
    {
        if (Auth::check()) {
            return view('dashboard');
        }
        return view('imago');
    }

    public function process(Request $request)
    {
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,webp|max:10240',
            'mode' => 'required|in:removebg,grayscale',
        ]);

        $file = $request->file('image');
        $mode = $request->input('mode');

        $originalFilename = time() . '_' . $file->getClientOriginalName();
        $originalPath = $file->storeAs('originals', $originalFilename, 'public');

        $fastapiUrl = 'http://127.0.0.1:8001/process-image';

        // --- ADDED ERROR HANDLING ---
        try {
            $response = Http::attach(
                'file', file_get_contents($file), $file->getClientOriginalName()
            )->post($fastapiUrl, ['mode' => $mode,]);

            if (!$response->successful() || empty($response->json()['url'])) {
                return back()->with('error', 'AI service failed or returned an invalid response.');
            }

        } catch (ConnectionException $e) {
            // This new block catches the error if the Python server is down
            return back()->with('error', 'The AI processing service is currently unavailable. Please try again later.');
        }
        // --- END OF ERROR HANDLING ---

        $data = $response->json();
        $processedPath = $data['url'];

        if (Auth::check()) {
            ProcessedImage::create([
                'user_id' => Auth::id(),
                'original_path' => $originalPath,
                'processed_path' => $processedPath,
            ]);
        }

        $viewName = Auth::check() ? 'dashboard' : 'imago';

        return view($viewName, [
            'originalUrl' => Storage::url($originalPath),
            'processedUrl' => Storage::url($processedPath)
        ]);
    }
}
