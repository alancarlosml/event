<?php

declare(strict_types=1);

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class ParticipanteSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $participantes = [
            [
                'name' => 'João Silva',
                'email' => 'joao.silva@example.com',
                'password' => Hash::make('12345678'),
                'cpf' => '12345678901',
                'phone' => '(11) 98765-4321',
                'status' => 1,
            ],
            [
                'name' => 'Maria Santos',
                'email' => 'maria.santos@example.com',
                'password' => Hash::make('12345678'),
                'cpf' => '23456789012',
                'phone' => '(21) 98765-4321',
                'status' => 1,
            ],
            [
                'name' => 'Pedro Oliveira',
                'email' => 'pedro.oliveira@example.com',
                'password' => Hash::make('12345678'),
                'cpf' => '34567890123',
                'phone' => '(31) 98765-4321',
                'status' => 1,
            ],
            [
                'name' => 'Ana Costa',
                'email' => 'ana.costa@example.com',
                'password' => Hash::make('12345678'),
                'cpf' => '45678901234',
                'phone' => '(41) 98765-4321',
                'status' => 1,
            ],
            [
                'name' => 'Carlos Ferreira',
                'email' => 'carlos.ferreira@example.com',
                'password' => Hash::make('12345678'),
                'cpf' => '56789012345',
                'phone' => '(51) 98765-4321',
                'status' => 1,
            ],
            [
                'name' => 'Juliana Alves',
                'email' => 'juliana.alves@example.com',
                'password' => Hash::make('12345678'),
                'cpf' => '67890123456',
                'phone' => '(11) 97654-3210',
                'status' => 1,
            ],
            [
                'name' => 'Roberto Lima',
                'email' => 'roberto.lima@example.com',
                'password' => Hash::make('12345678'),
                'cpf' => '78901234567',
                'phone' => '(21) 97654-3210',
                'status' => 1,
            ],
            [
                'name' => 'Fernanda Rocha',
                'email' => 'fernanda.rocha@example.com',
                'password' => Hash::make('12345678'),
                'cpf' => '89012345678',
                'phone' => '(31) 97654-3210',
                'status' => 1,
            ],
        ];

        foreach ($participantes as $participante) {
            DB::table('participantes')->insert([
                'name' => $participante['name'],
                'email' => $participante['email'],
                'password' => $participante['password'],
                'cpf' => $participante['cpf'],
                'phone' => $participante['phone'],
                'status' => $participante['status'],
                'email_verified_at' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
