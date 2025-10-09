<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;

class ImageController extends Controller
{
    public function index(): View
    {
        return view('imago'); // Ensure this matches your blade file name
    }

    public function process(Request $request): JsonResponse
    {
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,webp|max:10240', // 10MB Max
            'mode' => 'required|in:removebg,grayscale'
        ]);

        $file = $request->file('image');
        $mode = $request->input('mode');

        // The endpoint URL for your FastAPI service
        $fastapiUrl = 'http://127.0.0.1:8001/process-image';

        $response = Http::attach(
            'file', file_get_contents($file), $file->getClientOriginalName()
        )->post($fastapiUrl, [
            'mode' => $mode,
            // You can add other parameters here if your model needs them
            // 'scale' => 4,
        ]);

        if (!$response->successful()) {
            // Forward the error from the Python service
            return response()->json(
                ['message' => 'AI service failed', 'details' => $response->json() ?? $response->body()],
                502 // Bad Gateway
            );
        }

        $data = $response->json();

        if (empty($data['url'])) {
            return response()->json(['message' => 'Invalid response from AI service'], 500);
        }

        // The url() helper creates a full, absolute URL, which is exactly what the frontend needs
        $processedUrl = url($data['url']);

        return response()->json(['processed_url' => $processedUrl]);
    }
}
