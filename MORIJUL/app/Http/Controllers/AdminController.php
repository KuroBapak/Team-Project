<?php

namespace App\Http\Controllers;

use App\Models\Admin; // Pastikan model Admin sudah ada
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
        $order = Order::findOrFail($id);
        $order->delete();

        return redirect()->route('admin.dashboard')->with('status', 'Order deleted successfully!');
    }

    public function dashboard()
    {
        $orders = Order::with('items.product')->get(); // Load order items with products

        return view('admin.products.dashboard', compact('orders'));
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
