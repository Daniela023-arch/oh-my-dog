<?php

namespace Database\Seeders;

use App\Models\Pet;
use App\Models\User;
use Illuminate\Database\Seeder;

class PetSeeder extends Seeder
{
    /**
     * Crear mascotas de prueba.
     */
    public function run(): void
    {
        $pets = [
            [
                'owner' => 'ana@ohmydog.com',
                'name' => 'Max',
                'species' => 'Perro',
                'breed' => 'Golden Retriever',
                'sex' => 'macho',
                'birth_date' => '2022-05-15',
                'weight' => 28.50,
                'notes' => 'Es tranquilo y sociable. No tiene alergias conocidas.',
            ],
            [
                'owner' => 'ana@ohmydog.com',
                'name' => 'Luna',
                'species' => 'Perro',
                'breed' => 'Poodle',
                'sex' => 'hembra',
                'birth_date' => '2023-08-10',
                'weight' => 7.20,
                'notes' => 'Tiene piel sensible. Utilizar champú hipoalergénico.',
            ],
            [
                'owner' => 'carlos@ohmydog.com',
                'name' => 'Rocky',
                'species' => 'Perro',
                'breed' => 'Bulldog Francés',
                'sex' => 'macho',
                'birth_date' => '2021-11-20',
                'weight' => 12.40,
                'notes' => 'Se pone nervioso durante el baño. Tratar con paciencia.',
            ],
            [
                'owner' => 'carlos@ohmydog.com',
                'name' => 'Nala',
                'species' => 'Perro',
                'breed' => 'Chihuahua',
                'sex' => 'hembra',
                'birth_date' => '2024-02-14',
                'weight' => 3.10,
                'notes' => 'Es pequeña y muy activa. Prefiere agua tibia.',
            ],
            [
                'owner' => 'maria@ohmydog.com',
                'name' => 'Toby',
                'species' => 'Perro',
                'breed' => 'Schnauzer',
                'sex' => 'macho',
                'birth_date' => '2020-07-08',
                'weight' => 9.80,
                'notes' => 'Requiere corte de pelo estilo Schnauzer.',
            ],
            [
                'owner' => 'maria@ohmydog.com',
                'name' => 'Maya',
                'species' => 'Perro',
                'breed' => 'Husky Siberiano',
                'sex' => 'hembra',
                'birth_date' => '2022-12-05',
                'weight' => 23.60,
                'notes' => 'Tiene abundante pelaje. Se recomienda deslanado.',
            ],
            [
                'owner' => 'luis@ohmydog.com',
                'name' => 'Bruno',
                'species' => 'Perro',
                'breed' => 'Labrador Retriever',
                'sex' => 'macho',
                'birth_date' => '2021-03-18',
                'weight' => 30.20,
                'notes' => 'Es muy amigable. Necesita limpieza de oídos.',
            ],
            [
                'owner' => 'luis@ohmydog.com',
                'name' => 'Kira',
                'species' => 'Perro',
                'breed' => 'Pomerania',
                'sex' => 'hembra',
                'birth_date' => '2023-09-22',
                'weight' => 4.50,
                'notes' => 'Tiene pelaje delicado. Evitar cortes demasiado cortos.',
            ],
        ];

        foreach ($pets as $data) {

            // Buscar al cliente propietario.
            $owner = User::where(
                'email',
                $data['owner']
            )->firstOrFail();

            // Retirar el correo auxiliar.
            unset($data['owner']);

            // Asignar el propietario.
            $data['user_id'] = $owner->id;
            $data['is_active'] = true;

            // Crear o actualizar la mascota.
            Pet::updateOrCreate(
                [
                    'user_id' => $owner->id,
                    'name' => $data['name'],
                ],
                $data
            );
        }
    }
}
