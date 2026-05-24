<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['username' => 'admin'],
            [
                'name'              => 'Admin',
                'email'             => 'admin@funtastichouse.pt',
                'username'          => 'admin',
                'password'          => bcrypt('Admin1212@'),
                'is_admin'          => true,
                'email_verified_at' => now(),
            ]
        );
    }
}
