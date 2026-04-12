<?php

namespace Database\Seeders;

use App\Models\Admin;
use App\Models\BankAccounts;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class PerfectAdminSeeder extends Seeder
{
    public function run(): void
    {
        $admin = Admin::create([
            'name' => 'Super Admin',
            'email' => 'admin@billboard.com',
            'img' => 'img/profile-pictures/default.svg',
            'password' => Hash::make('admin123'),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Create Bank Account for Admin
        BankAccounts::create([
            'user_type' => 'admin',
            'user_id' => $admin->id,
            'current_balance' => 100000,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}