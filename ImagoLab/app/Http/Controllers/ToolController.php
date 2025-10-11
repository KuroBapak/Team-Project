<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ToolController extends Controller
{
    /**
     * Show the tool selection page.
     */
    public function selection()
    {
        return view('selection');
    }

    /**
     * Store the user's choice in the session and redirect them to the editor.
     */
    public function storeSelection(Request $request)
    {
        $validated = $request->validate([
            'tool_type' => 'required|in:basic,advanced'
        ]);

        // We store the choice in the user's session for this visit.
        session(['tool_type' => $validated['tool_type']]);

        // Redirect to the main editor page.
        return redirect()->route('editor');
    }
}
