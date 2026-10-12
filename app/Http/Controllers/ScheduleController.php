<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\Schedule;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class ScheduleController extends Controller
{
    /**
     * Mostrar los horarios al encargado.
     */
    public function index(Request $request): View
    {
        $this->authorizeManager($request);

        $schedules = Schedule::query()
            ->weekOrder()
            ->get();

        return view(
            'encargado.horarios.index',
            compact('schedules')
        );
    }

    /**
     * Mostrar el formulario para editar un día.
     */
    public function edit(
        Request $request,
        Schedule $schedule
    ): View {
        $this->authorizeManager($request);

        return view(
            'encargado.horarios.edit',
            compact('schedule')
        );
    }

    /**
     * Actualizar un horario.
     */
    public function update(
        Request $request,
        Schedule $schedule
    ): RedirectResponse {
        $this->authorizeManager($request);

        $validated = $request->validate([
            'opening_time' => [
                'required',
                'date_format:H:i',
            ],
            'closing_time' => [
                'required',
                'date_format:H:i',
                'after:opening_time',
            ],
            'slot_minutes' => [
                'required',
                'integer',
                'min:5',
                'max:240',
            ],
            'is_active' => [
                'required',
                'boolean',
            ],
            'notes' => [
                'nullable',
                'string',
                'max:255',
            ],
        ]);

        $openingTime = $validated['opening_time'] . ':00';
        $closingTime = $validated['closing_time'] . ':00';
        $isActive = (bool) $validated['is_active'];

        DB::transaction(function () use (
            $schedule,
            $validated,
            $openingTime,
            $closingTime,
            $isActive
        ) {
            // Evitar cambios que afecten citas confirmadas.
            $hasAffectedAppointments = Appointment::query()
                ->where('status', 'confirmada')
                ->whereDate(
                    'appointment_date',
                    '>=',
                    today()->toDateString()
                )
                ->whereRaw(
                    "CAST(strftime('%w', appointment_date) AS INTEGER) = ?",
                    [$schedule->day_of_week]
                )
                ->where(function ($query) use (
                    $openingTime,
                    $closingTime,
                    $isActive
                ) {
                    if (!$isActive) {
                        return;
                    }

                    $query->where(
                        'start_time',
                        '<',
                        $openingTime
                    )->orWhere(
                        'end_time',
                        '>',
                        $closingTime
                    );
                })
                ->exists();

            if ($hasAffectedAppointments) {
                throw ValidationException::withMessages([
                    'opening_time' =>
                        'No puedes aplicar este cambio porque existen citas confirmadas que quedarían fuera del nuevo horario o en un día cerrado.',
                ]);
            }

            $schedule->update([
                'opening_time' => $openingTime,
                'closing_time' => $closingTime,
                'slot_minutes' =>
                    $validated['slot_minutes'],
                'is_active' => $isActive,
                'notes' => $validated['notes'] ?? null,
            ]);
        });

        return redirect()
            ->route('encargado.horarios.index')
            ->with(
                'success',
                'El horario se actualizó correctamente.'
            );
    }

    /**
     * Mostrar horarios públicos de atención.
     */
    public function publicHours(): View
    {
        $schedules = Schedule::query()
            ->weekOrder()
            ->get();

        return view(
            'public.horarios',
            compact('schedules')
        );
    }

    /**
     * Consultar las horas de inicio disponibles.
     *
     * Recibe:
     * - date: fecha solicitada
     * - duration: duración total en minutos
     *
     * Devuelve JSON para el formulario de citas.
     */
    public function availability(Request $request)
    {
        $validated = $request->validate([
            'date' => [
                'required',
                'date_format:Y-m-d',
                'after_or_equal:today',
            ],
            'duration' => [
                'required',
                'integer',
                'min:1',
                'max:1440',
            ],
        ]);

        $date = Carbon::createFromFormat(
            '!Y-m-d',
            $validated['date']
        );

        $schedule = Schedule::where(
            'day_of_week',
            $date->dayOfWeek
        )->first();

        if (!$schedule || !$schedule->is_active) {
            return response()->json([
                'available' => false,
                'message' => 'El establecimiento está cerrado este día.',
                'slots' => [],
            ]);
        }

        $duration = (int) $validated['duration'];

        $opening = Carbon::parse(
            $date->toDateString() . ' ' .
            $schedule->opening_time
        );

        $closing = Carbon::parse(
            $date->toDateString() . ' ' .
            $schedule->closing_time
        );

        $current = $opening->copy();
        $slots = [];

        while ($current->lessThan($closing)) {
            $end = $current->copy()
                ->addMinutes($duration);

            if ($end->greaterThan($closing)) {
                break;
            }

            // No mostrar horarios que ya pasaron.
            if ($current->lessThanOrEqualTo(now())) {
                $current->addMinutes(
                    $schedule->slot_minutes
                );
                continue;
            }

            $startTime = $current->format('H:i:s');
            $endTime = $end->format('H:i:s');

            $occupied = Appointment::query()
                ->overlapping(
                    $date->toDateString(),
                    $startTime,
                    $endTime
                )
                ->exists();

            if (!$occupied) {
                $slots[] = [
                    'start' => $current->format('H:i'),
                    'end' => $end->format('H:i'),
                ];
            }

            $current->addMinutes(
                $schedule->slot_minutes
            );
        }

        return response()->json([
            'available' => count($slots) > 0,
            'date' => $date->toDateString(),
            'slots' => $slots,
        ]);
    }

    /**
     * Permitir administración solo al encargado.
     */
    private function authorizeManager(
        Request $request
    ): void {
        $user = $request->user();

        abort_unless(
            $user !== null
                && $user->is_active
                && $user->role === 'encargado',
            403,
            'No tienes permiso para administrar horarios.'
        );
    }
}
