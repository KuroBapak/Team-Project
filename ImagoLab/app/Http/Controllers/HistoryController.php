<?php

namespace App\Http\Controllers;

use App\Models\ProcessedImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class HistoryController extends Controller
{
    /**
     * Show the filterable and sortable history page.
     */
    public function index(Request $request)
    {
        // Start a query for the logged-in user's images
        $query = Auth::user()->processedImages();

        // --- Handle Filtering ---
        // Filter by tool type ('basic' or 'advanced')
        $query->when($request->input('tool_type'), function ($q, $toolType) {
            if ($toolType !== 'all') {
                return $q->where('tool_type', $toolType);
            }
        });

        // Filter by date range
        $query->when($request->input('date_range'), function ($q, $dateRange) {
            if ($dateRange === 'today') return $q->whereDate('created_at', today());
            if ($dateRange === 'week') return $q->where('created_at', '>=', now()->subDays(7));
            if ($dateRange === 'month') return $q->where('created_at', '>=', now()->subMonths(1));
            if ($dateRange === 'year') return $q->whereYear('created_at', now()->year);
        });

        // --- Handle Sorting ---
        $sortBy = $request->input('sort_by', 'newest'); // Default to newest
        if ($sortBy === 'oldest') {
            $query->orderBy('created_at', 'asc');
        } else {
            $query->orderBy('created_at', 'desc'); // 'newest' is the default
        }

        // Get all images before pagination for stats
        $allUserImages = Auth::user()->processedImages;

        // Execute the query with pagination
        $images = $query->paginate(9)->withQueryString(); // withQueryString keeps filters on page change

        return view('history', [
            'images' => $images,
            'totalEdits' => $allUserImages->count(),
            'basicEdits' => $allUserImages->where('tool_type', 'basic')->count(),
            'advancedEdits' => $allUserImages->where('tool_type', 'advanced')->count(),
        ]);
    }

    /**
     * Delete all history items for the logged-in user.
     */
    public function clearAll()
    {
        $images = Auth::user()->processedImages()->get();

        foreach ($images as $image) {
            // Delete the physical files
            Storage::disk('public')->delete($image->original_path);
            Storage::disk('public')->delete($image->processed_path);
            // Delete the database record
            $image->delete();
        }

        return redirect()->route('history.index')->with('success', 'All history has been cleared.');
    }

    // Your existing download() and destroy() methods are perfect and remain unchanged.
    public function download(ProcessedImage $image)
    {
        if ($image->user_id !== Auth::id()) abort(403);
        $filePath = Storage::disk('public')->path($image->processed_path);
        return response()->download($filePath);
    }
    public function destroy(ProcessedImage $image)
    {
        if ($image->user_id !== Auth::id()) abort(403);
        Storage::disk('public')->delete($image->original_path);
        Storage::disk('public')->delete($image->processed_path);
        $image->delete();
        return redirect()->route('history.index')->with('success', 'Image history deleted successfully.');
    }
}
