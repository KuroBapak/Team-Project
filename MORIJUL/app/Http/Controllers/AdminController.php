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

    public function updatePaymentStatus($id)
    {
        $order = Order::findOrFail($id);
        $order->payment_status = 'done'; // Update status to 'done'
        $order->save();

        return redirect()->route('admin.dashboard')->with('status', 'Payment status updated!');
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
    // Validasi input
    $request->validate([
        'username' => 'required',
        'password' => 'required',
    ]);

    // Cek user berdasarkan username
    $admin = Admin::where('username', $request->username)->first();

    // Jika user ditemukan dan password cocok
    if ($admin && Hash::check($request->password, $admin->password)) {
        // Simpan admin dalam session
        session(['admin' => $admin->id]);

        // Redirect ke dashboard admin
        return redirect()->route('admin.dashboard');
    }

    // Jika gagal login
    return back()->withErrors(['login' => 'Username atau password salah']);
}


public function logout()
{
    session()->forget('admin'); // Hapus session admin
    return redirect()->route('login'); // Arahkan ke halaman login
}

}
