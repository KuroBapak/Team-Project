<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ToolController extends Controller
{
    public function selection()
    {
        return view('selection');
    }

    public function storeSelection(Request $request)
    {
        $validated = $request->validate([
            'tool_type' => 'required|in:basic,advanced,canvas'
        ]);

        $toolType = $validated['tool_type'];

        // THIS IS THE NEW LOGIC
        if ($toolType === 'canvas') {
            // If user chose canvas, go to the new, separate route
            session(['tool_type' => 'canvas']);
            return redirect()->route('canvas.editor');
        }

        // Otherwise, use the existing logic for basic/advanced tools
        session(['tool_type' => $toolType]);
        return redirect()->route('editor');
    }

    // THIS IS THE NEW METHOD TO SHOW THE CANVAS VIEW
    public function showCanvasEditor()
    {
        // Check if the session is correctly set, otherwise redirect
        if (session('tool_type') !== 'canvas') {
            return redirect()->route('selection');
        }
        return view('canvas-editor');
    }
}
