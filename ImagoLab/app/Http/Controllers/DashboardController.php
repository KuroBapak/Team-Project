<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Show the main application dashboard/landing page.
     */
    public function index()
    {
        return view('dashboard');
    }
}
