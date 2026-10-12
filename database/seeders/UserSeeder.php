<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Crear usuarios de prueba.
     */
    public function run(): void
    {
        $users = [
            [
                'name' => 'Administrador Oh My Dog',
                'email' => 'encargado@ohmydog.com',
                'phone' => '5512345678',
                'password' => 'Encargado123!',
                'role' => 'encargado',
                'is_active' => true,
            ],
            [
                'name' => 'Ana Martínez',
                'email' => 'ana@ohmydog.com',
                'phone' => '5511111111',
                'password' => 'Cliente123!',
                'role' => 'cliente',
                'is_active' => true,
            ],
            [
                'name' => 'Carlos López',
                'email' => 'carlos@ohmydog.com',
                'phone' => '5522222222',
                'password' => 'Cliente123!',
                'role' => 'cliente',
                'is_active' => true,
            ],
            [
                'name' => 'María González',
                'email' => 'maria@ohmydog.com',
                'phone' => '5533333333',
                'password' => 'Cliente123!',
                'role' => 'cliente',
                'is_active' => true,
            ],
            [
                'name' => 'Luis Hernández',
                'email' => 'luis@ohmydog.com',
                'phone' => '5544444444',
                'password' => 'Cliente123!',
                'role' => 'cliente',
                'is_active' => true,
            ],
        ];

        foreach ($users as $data) {
            User::updateOrCreate(
                ['email' => $data['email']],
                $data
            );
        }
    }
}
