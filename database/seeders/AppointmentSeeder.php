<?php

namespace Database\Seeders;

use App\Models\Appointment;
use App\Models\Pet;
use App\Models\Schedule;
use App\Models\Service;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use RuntimeException;

class AppointmentSeeder extends Seeder
{
    /**
     * Crear citas de prueba.
     */
    public function run(): void
    {
        $manager = User::where(
            'email',
            'encargado@ohmydog.com'
        )->firstOrFail();

        $appointments = [
            [
                'days_from_today' => 1,
                'time' => '09:00',
                'owner' => 'ana@ohmydog.com',
                'pet' => 'Max',
                'services' => ['Baño básico'],
                'status' => 'pendiente',
                'notes' => 'Solicito baño para Max.',
            ],
            [
                'days_from_today' => 1,
                'time' => '11:00',
                'owner' => 'carlos@ohmydog.com',
                'pet' => 'Rocky',
                'services' => [
                    'Baño y corte',
                    'Corte de uñas',
                ],
                'status' => 'confirmada',
                'notes' => 'Tratar con paciencia durante el baño.',
            ],
            [
                'days_from_today' => 2,
                'time' => '10:00',
                'owner' => 'maria@ohmydog.com',
                'pet' => 'Toby',
                'services' => ['Paquete completo'],
                'status' => 'confirmada',
                'notes' => 'Solicito corte estilo Schnauzer.',
            ],
            [
                'days_from_today' => 2,
                'time' => '14:00',
                'owner' => 'luis@ohmydog.com',
                'pet' => 'Kira',
                'services' => ['Baño básico'],
                'status' => 'pendiente',
                'notes' => 'Utilizar productos suaves.',
            ],
            [
                'days_from_today' => 3,
                'time' => '09:30',
                'owner' => 'ana@ohmydog.com',
                'pet' => 'Luna',
                'services' => ['Baño y corte'],
                'status' => 'rechazada',
                'notes' => 'Solicito atención para Luna.',
                'rejection_reason' => 'No hay personal disponible para ese horario.',
            ],
            [
                'days_from_today' => 4,
                'time' => '12:00',
                'owner' => 'carlos@ohmydog.com',
                'pet' => 'Nala',
                'services' => ['Corte de uñas'],
                'status' => 'cancelada',
                'notes' => 'Cita cancelada por el cliente.',
            ],
            [
                'days_from_today' => -2,
                'time' => '09:00',
                'owner' => 'maria@ohmydog.com',
                'pet' => 'Maya',
                'services' => ['Deslanado'],
                'status' => 'atendida',
                'notes' => 'Retirar el exceso de pelo.',
                'attention_notes' => 'Servicio realizado satisfactoriamente.',
            ],
            [
                'days_from_today' => -3,
                'time' => '11:00',
                'owner' => 'luis@ohmydog.com',
                'pet' => 'Bruno',
                'services' => [
                    'Baño básico',
                    'Limpieza de oídos',
                ],
                'status' => 'atendida',
                'notes' => 'Revisar cuidadosamente los oídos.',
                'attention_notes' => 'Baño y limpieza realizados.',
            ],
        ];

        foreach ($appointments as $index => $data) {
            $this->createAppointment(
                $data,
                $manager,
                $index + 1
            );
        }
    }

    /**
     * Registrar una cita con sus servicios.
     */
    private function createAppointment(
        array $data,
        User $manager,
        int $number
    ): void {
        $owner = User::where(
            'email',
            $data['owner']
        )->firstOrFail();

        $pet = Pet::where('user_id', $owner->id)
            ->where('name', $data['pet'])
            ->firstOrFail();

        $services = Service::whereIn(
            'name',
            $data['services']
        )->get();

        if ($services->count() !== count($data['services'])) {
            throw new RuntimeException(
                'No se encontraron todos los servicios de la cita.'
            );
        }

        $duration = (int) $services->sum(
            'duration_minutes'
        );

        // Buscar un día abierto a partir de la fecha deseada.
        $date = Carbon::today()->addDays(
            $data['days_from_today']
        );

        $direction = $data['days_from_today'] < 0
            ? -1
            : 1;

        for ($attempt = 0; $attempt < 7; $attempt++) {
            $schedule = Schedule::where(
                'day_of_week',
                $date->dayOfWeek
            )->first();

            if ($schedule && $schedule->is_active) {
                break;
            }

            $date->addDays($direction);
        }

        if (
            !isset($schedule) ||
            !$schedule ||
            !$schedule->is_active
        ) {
            throw new RuntimeException(
                'No se encontró un día de atención.'
            );
        }

        $start = Carbon::parse(
            $date->toDateString() . ' ' . $data['time']
        );

        $end = $start->copy()->addMinutes(
            $duration
        );

        $startTime = $start->format('H:i:s');
        $endTime = $end->format('H:i:s');

        // Comprobar que la cita termine antes del cierre.
        if (!$schedule->allowsTime($startTime, $endTime)) {
            throw new RuntimeException(
                'La cita de ' . $data['pet'] .
                ' está fuera del horario de atención.'
            );
        }

        // Las citas confirmadas no deben traslaparse.
        if ($data['status'] === 'confirmada') {
            $hasConflict = Appointment::query()
                ->overlapping(
                    $date->toDateString(),
                    $startTime,
                    $endTime
                )
                ->exists();

            if ($hasConflict) {
                throw new RuntimeException(
                    'Conflicto de horario en la cita de ' .
                    $data['pet']
                );
            }
        }

        $status = $data['status'];

        $isReviewed = in_array(
            $status,
            ['confirmada', 'rechazada', 'atendida'],
            true
        );

        DB::transaction(function () use (
            $data,
            $manager,
            $owner,
            $pet,
            $services,
            $date,
            $startTime,
            $endTime,
            $status,
            $isReviewed,
            $number
        ) {
            $appointment = Appointment::create([
                'user_id' => $owner->id,
                'pet_id' => $pet->id,
                'appointment_date' => $date->toDateString(),
                'start_time' => $startTime,
                'end_time' => $endTime,
                'status' => $status,
                'notes' => $data['notes'] ?? null,
                'rejection_reason' =>
                    $data['rejection_reason'] ?? null,
                'managed_by' => $isReviewed
                    ? $manager->id
                    : null,
                'reviewed_at' => $isReviewed
                    ? now()
                    : null,
                'attention_notes' =>
                    $data['attention_notes'] ?? null,
                'attended_at' => $status === 'atendida'
                    ? now()
                    : null,
                'cancelled_at' => $status === 'cancelada'
                    ? now()
                    : null,
            ]);

            foreach ($services as $service) {
                $appointment->services()->attach(
                    $service->id,
                    [
                        'price' => $service->price,
                        'duration_minutes' =>
                            $service->duration_minutes,
                    ]
                );
            }
        });
    }
}
