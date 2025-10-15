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
        if ($request->has('canvas_data')) {
            $canvasData = $request->input('canvas_data');
            $image = str_replace('data:image/png;base64,', '', $canvasData);
            $image = str_replace(' ', '+', $image);

            // give it a nice filename like your old uploads
            $timestamp = now()->format('Ymd_His');
            $originalFilename = "canvas_{$timestamp}.png";

            // save to processed folder (since no "original upload")
            $processedPath = 'processed/' . $originalFilename;

            Storage::disk('public')->put($processedPath, base64_decode($image));

            $toolType = 'canvas';

            if (Auth::check()) {
                ProcessedImage::create([
                    'user_id'       => Auth::id(),
                    'tool_type'     => $toolType,
                    'original_path' => 'originals/' . $originalFilename, // mimic old style
                    'processed_path'=> $processedPath,
                ]);
            }

            // return silently without message popups
            return response()->json([
                'success'      => true,
                'processedUrl' => Storage::url($processedPath),
            ]);
        }

    // ✅ Old file upload + AI processing flow
    $request->validate([
        'image' => 'required|image|mimes:jpeg,png,jpg,webp|max:10240',
        'mode' => [
            'required',
            'in:removebg,superres,grayscale,brightness_contrast,rotate,resize,flip,gamma,threshold,histogram_equalization,sharpen,sobel_edge,morphology,hue_saturation,contrast_stretching,gaussian_blur,mean_blur,median_blur,laplacian_edge,prewitt_edge,low_pass_filter,high_pass_filter'
        ],
    ]);

    $file = $request->file('image');
    $originalFilename = time() . '_' . $file->getClientOriginalName();
    $originalPath = $file->storeAs('originals', $originalFilename, 'public');

    $fastapiUrl = 'http://127.0.0.1:8001/process-image';

    try {
        $response = Http::timeout(180)->attach(
            'file', file_get_contents($file), $file->getClientOriginalName()
        )->post($fastapiUrl, $request->all());

        if (!$response->successful() || empty($response->json()['url'])) {
            if ($request->wantsJson()) {
                return response()->json(['message' => 'AI service failed or returned an invalid response.'], 500);
            }
            return back()->with('error', 'AI service failed or returned an invalid response.');
        }

    } catch (ConnectionException $e) {
        if ($request->wantsJson()) {
            return response()->json(['message' => 'The AI processing service is currently unavailable.'], 503);
        }
        return back()->with('error', 'The AI processing service is currently unavailable or timed out. Please try again later.');
    }

    $data = $response->json();
    $processedPath = $data['url'];
    $toolType = session('tool_type', 'basic');

    if (Auth::check()) {
        ProcessedImage::create([
            'user_id'       => Auth::id(),
            'tool_type'     => $toolType,
            'original_path' => $originalPath,
            'processed_path'=> $processedPath,
        ]);
    }

    if ($request->wantsJson()) {
        return response()->json([
            'success'      => true,
            'message'      => 'Image processed and saved to history.',
            'processedUrl' => Storage::url($processedPath)
        ]);
    }

    $viewName = Auth::check() ? 'user' : 'guest';
    return view($viewName, [
        'toolType'    => $toolType,
        'originalUrl' => Storage::url($originalPath),
        'processedUrl'=> Storage::url($processedPath)
    ]);
}
}
