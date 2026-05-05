<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class ProfileUsersSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $users = [
            ['name' => 'Client Demo', 'email' => 'client@dmoc.test', 'role' => User::ROLE_CLIENT],
            //['name' => 'Vendeur Demo', 'email' => 'vendeur@dmoc.test', 'role' => User::ROLE_VENDOR],
            ['name' => 'Livreur Demo', 'email' => 'livreur@dmoc.test', 'role' => User::ROLE_COURIER],
            ['name' => 'Admin Demo', 'email' => 'admin@dmoc.test', 'role' => User::ROLE_ADMIN],
            //['name' => 'Superadmin Demo', 'email' => 'superadmin@dmoc.test', 'role' => User::ROLE_SUPERADMIN],
        ];

        foreach ($users as $user) {
            User::query()->firstOrCreate(
                ['email' => $user['email']],
                [
                    'name' => $user['name'],
                    'password' => 'password',
                    'role' => $user['role'],
                ]
            );
        }
    }
}
