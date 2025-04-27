<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Admin;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create a default admin user
        Admin::create([
            'username' => 'moreno',
            'password' => Hash::make('admin'),
            'role'     => 'admin',
        ]);

        // (Optional) Create a sample delivery user
        Admin::create([
            'username' => 'panjul',
            'password' => Hash::make('delivery'),
            'role'     => 'delivery',
        ]);
    }
}
