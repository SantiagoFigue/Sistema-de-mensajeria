<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Ejecutar los seeders de la base de datos.
     * Crea usuarios de prueba: 1 admin y 1 user
     *
     * @return void
     */
    public function run()
    {
        // Crear usuario administrador
        User::create([
            'name' => 'Administrador',
            'email' => 'admin@inbox.com',
            'password' => Hash::make('admin123'),
            'role' => 'admin',
            'email_verified_at' => now(),
        ]);

        // Crear usuario normal
        User::create([
            'name' => 'Usuario Normal',
            'email' => 'user@inbox.com',
            'password' => Hash::make('user123'),
            'role' => 'user',
            'email_verified_at' => now(),
        ]);

        $this->command->info('✓ Usuarios de prueba creados correctamente');
        $this->command->info('  Admin: admin@inbox.com / admin123');
        $this->command->info('  User: user@inbox.com / user123');
    }
}
