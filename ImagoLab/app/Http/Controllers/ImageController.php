<?php

namespace App\Http\Controllers;

use App\Models\ProcessedImage;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class ImageController extends Controller
{
    public function index(): View
    {
        $toolType = session('tool_type', 'basic');
        $viewName = Auth::check() ? 'user' : 'guest';
        return view($viewName, ['toolType' => $toolType]);
    }

    public function process(Request $request)
    {
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,webp|max:10240',

            // THE FIX: Added all the new basic tool modes to the validation list
            'mode' => [
                'required',
                'in:removebg,superres,grayscale,brightness_contrast,rotate,resize,flip,gamma,threshold,saturation,histogram_equalization,blur,sharpen,sobel_edge,morphology'
            ],
        ]);

        $file = $request->file('image');
        $originalFilename = time() . '_' . $file->getClientOriginalName();
        $originalPath = $file->storeAs('originals', $originalFilename, 'public');

        $fastapiUrl = 'http://127.0.0.1:8001/process-image';

        try {
            // Pass all request data (including new slider values) to the API.
            $response = Http::timeout(180)->attach(
                'file', file_get_contents($file), $file->getClientOriginalName()
            )->post($fastapiUrl, $request->all());

            if (!$response->successful() || empty($response->json()['url'])) {
                return back()->with('error', 'AI service failed or returned an invalid response.');
            }

        } catch (ConnectionException $e) {
            return back()->with('error', 'The AI processing service is currently unavailable or timed out. Please try again later.');
        }

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
