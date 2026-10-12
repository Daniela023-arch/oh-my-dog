<?php

namespace Database\Seeders;

use App\Models\Schedule;
use Illuminate\Database\Seeder;

class ScheduleSeeder extends Seeder
{
    /**
     * Crear los horarios iniciales de atención.
     */
    public function run(): void
    {
        $schedules = [
            [
                'day_of_week' => 0,
                'opening_time' => '09:00:00',
                'closing_time' => '18:00:00',
                'slot_minutes' => 30,
                'is_active' => false,
                'notes' => 'Domingo cerrado. Día de descanso.',
            ],
            [
                'day_of_week' => 1,
                'opening_time' => '09:00:00',
                'closing_time' => '18:00:00',
                'slot_minutes' => 30,
                'is_active' => true,
                'notes' => 'Horario habitual de atención.',
            ],
            [
                'day_of_week' => 2,
                'opening_time' => '09:00:00',
                'closing_time' => '18:00:00',
                'slot_minutes' => 30,
                'is_active' => true,
                'notes' => 'Horario habitual de atención.',
            ],
            [
                'day_of_week' => 3,
                'opening_time' => '09:00:00',
                'closing_time' => '18:00:00',
                'slot_minutes' => 30,
                'is_active' => true,
                'notes' => 'Horario habitual de atención.',
            ],
            [
                'day_of_week' => 4,
                'opening_time' => '09:00:00',
                'closing_time' => '18:00:00',
                'slot_minutes' => 30,
                'is_active' => true,
                'notes' => 'Horario habitual de atención.',
            ],
            [
                'day_of_week' => 5,
                'opening_time' => '09:00:00',
                'closing_time' => '18:00:00',
                'slot_minutes' => 30,
                'is_active' => true,
                'notes' => 'Horario habitual de atención.',
            ],
            [
                'day_of_week' => 6,
                'opening_time' => '09:00:00',
                'closing_time' => '15:00:00',
                'slot_minutes' => 30,
                'is_active' => true,
                'notes' => 'Horario reducido de sábado.',
            ],
        ];

        foreach ($schedules as $data) {
            Schedule::updateOrCreate(
                [
                    'day_of_week' => $data['day_of_week'],
                ],
                $data
            );
        }
    }
}
