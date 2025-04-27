<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Chats;
use App\Models\Order;

class AdminChatController extends Controller
{
    public function dashboard()
    {
        $orders = Order::with('items.product')->get();

        // pluck returns a Collection<string>
        $codes = Chats::select('unique_code')
                      ->distinct()
                      ->orderBy('unique_code')
                      ->pluck('unique_code');

        return view('admin.products.dashboard', compact('orders','codes'));
    }

    public function fetch($code)
    {
        return response()->json(
            Chats::where('unique_code', $code)
                 ->orderBy('created_at')
                 ->get()
        );
    }

    // POST /admin/chats/{code}/send
    public function send(Request $request, $code)
    {
        $data = $request->validate([
            'message' => 'required|string',
        ]);

        $chat = Chats::create([
            'unique_code' => $code,
            'sender'      => 'admin',
            'message'     => $data['message'],
        ]);

        return response()->json($chat, 201);
    }

}
