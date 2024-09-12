<?php

// Database\Seeders\AdminSeeder.php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Admin;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
// database\seeders\AdminSeeder.php
public function run()
{
    // Buat user admin dengan password yang di-hash
    Admin::create([
        'username' => 'admin',
        'password' => Hash::make('admin'), // Enkripsi password
    ]);
}
}

