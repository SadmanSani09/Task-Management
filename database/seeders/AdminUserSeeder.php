<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        // Delete existing admin with same email to avoid duplicates
        User::where('email', 'admin12@gmail.com')->delete();

        User::create([
            'name'     => 'Super Admin',
            'email'    => 'admin12@gmail.com',
            'password' => Hash::make('@admin123'),
            'role'     => 'admin',
        ]);

        $this->command->info('✅ Admin created: admin12@gmail.com / @admin123');
    }
}