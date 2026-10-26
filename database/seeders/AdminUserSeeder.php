<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        if (User::query()->where('email', 'admin@tkx.test')->exists()) {
            return;
        }

        User::factory()->admin()->create([
            'name' => 'Admin',
            'email' => 'admin@tkx.test',
            'password' => '1234',
        ]);
    }
}
