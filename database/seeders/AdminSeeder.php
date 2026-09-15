<?php

namespace Database\Seeders;

use App\Models\Admin;
use Illuminate\Database\Seeder;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        Admin::firstOrCreate(
            ['email' => 'admin@velocitymarkets.test'],
            [
                'name' => 'System Admin',
                'password' => bcrypt('your-password-here'),
            ]
        );
    }
}
