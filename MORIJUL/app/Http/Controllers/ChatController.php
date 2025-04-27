<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Chats;
use App\Models\Order;

class ChatController extends Controller
{
    // 1) User enters their code
    public function start(Request $request)
    {
        $data = $request->validate([
            'unique_code' => 'required|string|exists:orders,verification_code',
        ]);

        $request->session()->put('chat_code', $data['unique_code']);

        // pull buyer name
        $name = Order::where('verification_code', $data['unique_code'])
                     ->value('buyer_name');
        $request->session()->put('chat_name', $name);

        return redirect()->back()->with('success','Chat code accepted.');
    }


    // 2) User sends a message
// in App\Http\Controllers\ChatController.php

// in app/Http/Controllers/ChatController.php

// in app/Http/Controllers/ChatController.php

public function send(Request $request)
{
    $data = $request->validate([
        'message' => 'required|string',
    ]);

    $chat = Chats::create([
        'unique_code' => $request->session()->get('chat_code'),
        'sender'      => 'user',
        'message'     => $data['message'],
    ]);

    // Always return JSON so your AJAX .json() works
    return response()->json([
        'message'    => $chat->message,
        'created_at' => $chat->created_at->toDateTimeString(),
    ], 201);
}



    // 3) (Optional) return JSON history for AJAX
    public function fetch(Request $request)
    {
        $code = $request->session()->get('chat_code');
        return response()->json(
            Chats::where('unique_code',$code)
                 ->orderBy('created_at')
                 ->get()
        );
    }

    public function reset(Request $request)
{
    $request->session()->forget(['chat_code','chat_name']);
    return response()->json(['status'=>'ok']);
}
}
