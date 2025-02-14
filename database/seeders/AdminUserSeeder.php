<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\UserData;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Crypt;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = User::create([
            'name' => 'Admin',
            'email' => 'walterrjr.86@gmail.com',
            'password' => Hash::make('Pmprparana2025!'),
            'two_factor_enabled' => true, // Habilita o 2FA
            'two_factor_type' => 'email', // Define o tipo como e-mail
        ]);

        // Criar UserData vinculado ao usuário
        UserData::create([
            'user_id' => $user->id,
            'cpf' => Crypt::encryptString('123.456.789-00'),
            'telefone' => Crypt::encryptString('(11) 4002-8922'),
            'celular' => Crypt::encryptString('(41) 99999-8888'),
            'cidade' => 'São Paulo',
            'estado' => 'SP',
            'oab' => Crypt::encryptString('123456'),
            'estado_oab' => 'SP',
            'data_nascimento' => '1990-01-01', // Mantém sem criptografia
        ]);
    }
}
