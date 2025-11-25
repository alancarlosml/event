<?php

declare(strict_types=1);

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Criar role super_admin para o guard participante
        Role::firstOrCreate(
            ['name' => 'super_admin', 'guard_name' => 'participante'],
            ['name' => 'super_admin', 'guard_name' => 'participante']
        );

        // Criar role organizador para o guard participante (opcional, para organização futura)
        Role::firstOrCreate(
            ['name' => 'organizador', 'guard_name' => 'participante'],
            ['name' => 'organizador', 'guard_name' => 'participante']
        );

        $this->command->info('Roles criadas com sucesso!');
    }
}

