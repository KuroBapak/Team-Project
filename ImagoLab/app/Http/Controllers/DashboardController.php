<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        // This controller now only shows the main landing page.
        return view('home');
    }
}
