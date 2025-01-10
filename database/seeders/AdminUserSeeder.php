<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'name' => 'Admin',
            'email' => 'walterrjr.86@gmail.com',
            'password' => Hash::make('Pmprparana2025!'),
            'two_factor_enabled' => true, // Habilita o 2FA
            'two_factor_type' => 'email', // Define o tipo como e-mail
        ]);
    }
}
