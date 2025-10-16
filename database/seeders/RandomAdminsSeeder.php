<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Admin;
use Illuminate\Support\Facades\Hash;
use Faker\Factory as Faker;

class RandomAdminsSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create();

        for ($i = 0; $i < 15; $i++) {
            Admin::create([
                'name' => $faker->name(),
                'email' => $faker->unique()->safeEmail(),
                'password' => Hash::make('password'),
                'can_create' => $faker->boolean(),
                'can_read' => $faker->boolean(),
                'can_update' => $faker->boolean(),
                'can_delete' => $faker->boolean(),
            ]);
        }
    }
}
