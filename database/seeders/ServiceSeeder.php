<?php

namespace Database\Seeders;

use App\Models\Service;
use Illuminate\Database\Seeder;

class ServiceSeeder extends Seeder
{
    /**
     * Crear el catálogo inicial de servicios.
     */
    public function run(): void
    {
        $services = [
            [
                'name' => 'Baño básico',
                'description' => 'Baño con champú especial para mascotas, secado profesional, cepillado y aplicación de perfume.',
                'price' => 180.00,
                'duration_minutes' => 45,
                'is_active' => true,
            ],
            [
                'name' => 'Baño y corte',
                'description' => 'Servicio completo de baño, secado, cepillado y corte de pelo de acuerdo con la raza y las necesidades de la mascota.',
                'price' => 300.00,
                'duration_minutes' => 90,
                'is_active' => true,
            ],
            [
                'name' => 'Corte de uñas',
                'description' => 'Corte y cuidado de uñas realizado con herramientas profesionales para garantizar la comodidad de la mascota.',
                'price' => 80.00,
                'duration_minutes' => 15,
                'is_active' => true,
            ],
            [
                'name' => 'Limpieza de oídos',
                'description' => 'Limpieza externa y cuidadosa de los oídos utilizando productos especiales para mascotas.',
                'price' => 70.00,
                'duration_minutes' => 15,
                'is_active' => true,
            ],
            [
                'name' => 'Deslanado',
                'description' => 'Eliminación de pelo muerto mediante cepillado especializado para mascotas con pelaje abundante.',
                'price' => 220.00,
                'duration_minutes' => 60,
                'is_active' => true,
            ],
            [
                'name' => 'Paquete completo',
                'description' => 'Servicio integral que incluye baño, corte de pelo, corte de uñas, limpieza de oídos, cepillado y perfume.',
                'price' => 450.00,
                'duration_minutes' => 120,
                'is_active' => true,
            ],
        ];

        foreach ($services as $data) {
            Service::updateOrCreate(
                [
                    'name' => $data['name'],
                ],
                $data
            );
        }
    }
}
