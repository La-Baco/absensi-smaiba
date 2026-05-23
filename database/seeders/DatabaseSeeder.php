<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        $userData = [
            [
                'name' => 'Admin',
                'email' => 'admin@test.com',
                'role' => 'admin',
                'password' => bcrypt('admin123')
            ],
            [
                'name' => 'Operator',
                'email' => 'operator@test.com',
                'role' => 'operator',
                'password' => bcrypt('operator123')
            ]

        ];

        foreach ($userData as $key => $val) {
            User::create($val);
        }
    }
}
