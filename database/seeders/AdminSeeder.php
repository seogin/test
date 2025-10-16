<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Admin;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        Admin::create([
            'name' => 'Super Admin',
            'email' => 'test@test.com',
            'password' => Hash::make('test123456'),
            'can_create' => true,
            'can_read' => true,
            'can_update' => true,
            'can_delete' => true,
            'can_create_members' => true,
            'can_read_members' => true,
            'can_update_members' => true,
            'can_delete_members' => true,
        ]);
    }
}

// namespace Database\Seeders;

// use Illuminate\Database\Seeder;
// use App\Models\Admin;
// use Illuminate\Support\Facades\Hash;
// use Illuminate\Support\Str;

// class AdminSeeder extends Seeder
// {
//     public function run(): void
//     {
//         // Generate a strong random password (20 chars)
//         $plainPassword = Str::password(20);

//         $admin = Admin::updateOrCreate(
//             ['email' => 'admin@matewealthy.com'],
//             [
//                 'name' => 'Super Admin',
//                 'password' => Hash::make($plainPassword),
//                 'can_create' => true,
//                 'can_read' => true,
//                 'can_update' => true,
//                 'can_delete' => true,
//             ]
//         );

//         // Show only in console (for local use)
//         $this->command?->info("Admin account seeded:");
//         $this->command?->info("  Email: {$admin->email}");
//         $this->command?->info("  Password: {$plainPassword}");
//     }
// }
