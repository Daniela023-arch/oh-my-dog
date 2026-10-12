<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Ejecutar todos los seeders del sistema.
     */
    public function run(): void
    {
        $this->call([
            UserSeeder::class,
            PetSeeder::class,
            ServiceSeeder::class,
            ScheduleSeeder::class,
            AppointmentSeeder::class,
        ]);
    }
}
