<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

use App\Models\Member;
class MemberSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Member::insert([
            [
                'name' => 'Alice Johnson',
                'email' => 'alice@example.com',
                'password' => Hash::make('password123'),
                'phone' => '123-456-7890',
                'city' => 'Vancouver',
                'state' => 'BC',
                'country' => 'Canada',
                'verified' => true,
                'subscription' => 'Paid',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Bob Smith',
                'email' => 'bob@example.com',
                'password' => Hash::make('password123'),
                'phone' => '604-987-6543',
                'city' => 'Toronto',
                'state' => 'ON',
                'country' => 'Canada',
                'verified' => false,
                'subscription' => 'Free',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
