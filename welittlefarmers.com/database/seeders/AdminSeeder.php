<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
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
        if (Admin::count() == 0) {
            Admin::create([
                'name' => 'Admin',
                'email' => 'admin@welittlefarmers.com',
                'password' => Hash::make('admin@123'),
                'phone' => null,
                'is_active' => true,
            ]);

            echo "Default admin created:\n";
            echo "Email: admin@welittlefarmers.com\n";
            echo "Password: password123\n";
            echo "Please change the password after first login!\n";
        } else {
            echo "Admin already exists. Skipping...\n";
        }
    }
}
