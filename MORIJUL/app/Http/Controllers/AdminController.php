<?php

namespace App\Http\Controllers;

use App\Models\Admin; // Pastikan model Admin sudah ada
use App\Models\Chats;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    public function showLoginForm()
    {
        return view('admin.login'); // Tampilan form login
    }

    public function updatePaymentStatus(Request $request, $id)
    {
        $order = Order::findOrFail($id);

        $request->validate([
            'code' => 'required|string',
        ]);

        if ($request->code !== $order->verification_code) {
            return back()->withErrors(['code' => 'Invalid verification code.']);
        }

        $order->payment_status = 'done';
        $order->save();

        return redirect()
               ->route('admin.dashboard')
               ->with('status', 'Payment status updated!');
    }


    // Method to delete an order
    public function deleteOrder($id)
    {
        // 1) Find the order (will 404 if not found)
        $order = Order::findOrFail($id);

        // 2) Grab its verification code before deleting
        $code = $order->verification_code;

        // 3) Delete the order itself
        $order->delete();

        // 4) Purge all chats tied to that code
        Chats::where('unique_code', $code)->delete();

        // 5) Redirect back
        return redirect()
               ->route('admin.dashboard')
               ->with('status', 'Order and its chat history deleted successfully!');
    }

    public function dashboard()
    {
        $orders = Order::with('items.product')->get();

        // Get all distinct chat codes
        $codes = Chats::distinct()
                      ->orderBy('unique_code')
                      ->pluck('unique_code');

        return view('admin.products.dashboard', compact('orders','codes'));
    }


    public function login(Request $request)
    {
        // 1. Validate input
        $data = $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        // 2. Retrieve user by username
        $user = Admin::where('username', $data['username'])->first();

        // 3. Verify password
        if ($user && Hash::check($data['password'], $user->password)) {
            // 4. Save ID & role
            session([
                'user_id' => $user->id,
                'role'    => $user->role,
                'admin_username'=> $user->username,
            ]);

            // 5. Redirect based on role
            if ($user->role === 'admin') {
                return redirect()->route('admin.dashboard');
            }
            return redirect()->route('delivery.dashboard');
        }

        // Auth failed
        return back()->withErrors(['login' => 'Username atau password salah']);
    }




    public function logout()
    {
        session()->forget(['user_id','role']);
        return redirect()->route('login');
    }


}
