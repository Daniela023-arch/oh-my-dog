<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\Pet;
use App\Models\Schedule;
use App\Models\Service;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class AppointmentController extends Controller
{
    /**
     * Listar las citas del cliente.
     */
    public function index(Request $request): View
    {
        $this->authorizeClient($request);

        $query = Appointment::query()
            ->with(['pet', 'services'])
            ->where('user_id', $request->user()->id);

        if ($request->filled('status')) {
            $status = $request->query('status');

            if (in_array($status, $this->statuses(), true)) {
                $query->where('status', $status);
            }
        }

        $appointments = $query
            ->orderByDesc('appointment_date')
            ->orderByDesc('start_time')
            ->paginate(10)
            ->withQueryString();

        return view(
            'cliente.citas.index',
            compact('appointments')
        );
    }

    /**
     * Mostrar formulario para solicitar una cita.
     */
    public function create(Request $request): View
    {
        $this->authorizeClient($request);

        $pets = $request->user()
            ->pets()
            ->where('is_active', true)
            ->orderBy('name')
            ->get();

        $services = Service::query()
            ->active()
            ->orderBy('name')
            ->get();

        return view(
            'cliente.citas.create',
            compact('pets', 'services')
        );
    }

    /**
     * Registrar una solicitud de cita.
     */
    public function store(Request $request): RedirectResponse
    {
        $this->authorizeClient($request);

        $validated = $request->validate([
            'pet_id' => [
                'required',
                'integer',
                Rule::exists('pets', 'id')->where(
                    'user_id',
                    $request->user()->id
                )->where('is_active', true),
            ],
            'services' => [
                'required',
                'array',
                'min:1',
            ],
            'services.*' => [
                'required',
                'integer',
                'distinct',
                Rule::exists('services', 'id')
                    ->where('is_active', true),
            ],
            'appointment_date' => [
                'required',
                'date_format:Y-m-d',
                'after_or_equal:today',
            ],
            'start_time' => [
                'required',
                'date_format:H:i',
            ],
            'notes' => [
                'nullable',
                'string',
                'max:2000',
            ],
        ]);

        $pet = Pet::query()
            ->where('user_id', $request->user()->id)
            ->where('is_active', true)
            ->findOrFail($validated['pet_id']);

        $services = Service::query()
            ->where('is_active', true)
            ->whereIn('id', $validated['services'])
            ->get();

        if ($services->count() !== count($validated['services'])) {
            throw ValidationException::withMessages([
                'services' => 'Selecciona servicios válidos y activos.',
            ]);
        }

        $duration = (int) $services->sum('duration_minutes');

        $date = Carbon::createFromFormat(
            '!Y-m-d',
            $validated['appointment_date']
        );

        $start = Carbon::parse(
            $date->toDateString() . ' ' .
            $validated['start_time']
        );

        $end = $start->copy()->addMinutes($duration);

        $this->validateSchedule(
            $date,
            $start,
            $end
        );

        DB::transaction(function () use (
            $request,
            $validated,
            $pet,
            $services,
            $date,
            $start,
            $end
        ) {
            $appointment = Appointment::create([
                'user_id' => $request->user()->id,
                'pet_id' => $pet->id,
                'appointment_date' => $date->toDateString(),
                'start_time' => $start->format('H:i:s'),
                'end_time' => $end->format('H:i:s'),
                'status' => 'pendiente',
                'notes' => $validated['notes'] ?? null,
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

        return redirect()
            ->route('cliente.citas.index')
            ->with(
                'success',
                'Tu solicitud de cita se registró correctamente. Está pendiente de confirmación.'
            );
    }

    /**
     * Mostrar detalles de una cita del cliente.
     */
    public function show(
        Request $request,
        Appointment $appointment
    ): View {
        $this->authorizeClient($request);
        $this->authorizeOwner($request, $appointment);

        $appointment->load([
            'pet',
            'services',
            'manager',
        ]);

        return view(
            'cliente.citas.show',
            compact('appointment')
        );
    }

    /**
     * Cancelar una cita propia.
     */
    public function cancel(
        Request $request,
        Appointment $appointment
    ): RedirectResponse {
        $this->authorizeClient($request);
        $this->authorizeOwner($request, $appointment);

        DB::transaction(function () use ($appointment) {
            $current = Appointment::query()
                ->whereKey($appointment->id)
                ->firstOrFail();

            if (!in_array(
                $current->status,
                ['pendiente', 'confirmada'],
                true
            )) {
                throw ValidationException::withMessages([
                    'appointment' =>
                        'Esta cita ya no puede cancelarse.',
                ]);
            }

            $start = Carbon::parse(
                $current->appointment_date->format('Y-m-d')
                . ' ' . $current->start_time
            );

            if ($start->lessThanOrEqualTo(now())) {
                throw ValidationException::withMessages([
                    'appointment' =>
                        'No puedes cancelar una cita que ya comenzó.',
                ]);
            }

            $current->update([
                'status' => 'cancelada',
                'cancelled_at' => now(),
            ]);
        });

        return redirect()
            ->route('cliente.citas.index')
            ->with(
                'success',
                'La cita se canceló correctamente.'
            );
    }

    /**
     * Listar todas las citas para el encargado.
     */
    public function managerIndex(Request $request): View
    {
        $this->authorizeManager($request);

        $query = Appointment::query()
            ->with(['user', 'pet', 'services']);

        if ($request->filled('status')) {
            $status = $request->query('status');

            if (in_array($status, $this->statuses(), true)) {
                $query->where('status', $status);
            }
        }

        if ($request->filled('date')) {
            $date = $request->validate([
                'date' => ['date_format:Y-m-d'],
            ])['date'];

            $query->whereDate('appointment_date', $date);
        }

        $appointments = $query
            ->orderByDesc('appointment_date')
            ->orderByDesc('start_time')
            ->paginate(15)
            ->withQueryString();

        return view(
            'encargado.citas.index',
            compact('appointments')
        );
    }

    /**
     * Mostrar detalles de una cita al encargado.
     */
    public function managerShow(
        Request $request,
        Appointment $appointment
    ): View {
        $this->authorizeManager($request);

        $appointment->load([
            'user',
            'pet',
            'services',
            'manager',
        ]);

        return view(
            'encargado.citas.show',
            compact('appointment')
        );
    }

    /**
     * Confirmar una cita pendiente.
     */
    public function confirm(
        Request $request,
        Appointment $appointment
    ): RedirectResponse {
        $this->authorizeManager($request);

        DB::transaction(function () use (
            $request,
            $appointment
        ) {
            // En SQLite, esta escritura inicial permite
            // serializar confirmaciones concurrentes.
            DB::table('appointments')
                ->where('id', $appointment->id)
                ->update(['updated_at' => now()]);

            $current = Appointment::query()
                ->findOrFail($appointment->id);

            if ($current->status !== 'pendiente') {
                throw ValidationException::withMessages([
                    'appointment' =>
                        'Solo puedes confirmar citas pendientes.',
                ]);
            }

            $date = $current->appointment_date;

            $start = Carbon::parse(
                $date->format('Y-m-d') .
                ' ' . $current->start_time
            );

            $end = Carbon::parse(
                $date->format('Y-m-d') .
                ' ' . $current->end_time
            );

            $this->validateSchedule(
                $date,
                $start,
                $end
            );

            $hasConflict = Appointment::query()
                ->whereKeyNot($current->id)
                ->overlapping(
                    $date->format('Y-m-d'),
                    $current->start_time,
                    $current->end_time
                )
                ->exists();

            if ($hasConflict) {
                throw ValidationException::withMessages([
                    'appointment' =>
                        'No se puede confirmar: existe otra cita confirmada en ese horario.',
                ]);
            }

            $current->update([
                'status' => 'confirmada',
                'managed_by' => $request->user()->id,
                'reviewed_at' => now(),
                'rejection_reason' => null,
            ]);
        }, 3);

        return back()->with(
            'success',
            'La cita se confirmó correctamente.'
        );
    }

    /**
     * Rechazar una cita pendiente.
     */
    public function reject(
        Request $request,
        Appointment $appointment
    ): RedirectResponse {
        $this->authorizeManager($request);

        $validated = $request->validate([
            'rejection_reason' => [
                'required',
                'string',
                'max:2000',
            ],
        ]);

        DB::transaction(function () use (
            $request,
            $appointment,
            $validated
        ) {
            $current = Appointment::query()
                ->findOrFail($appointment->id);

            if ($current->status !== 'pendiente') {
                throw ValidationException::withMessages([
                    'appointment' =>
                        'Solo puedes rechazar citas pendientes.',
                ]);
            }

            $current->update([
                'status' => 'rechazada',
                'managed_by' => $request->user()->id,
                'reviewed_at' => now(),
                'rejection_reason' =>
                    $validated['rejection_reason'],
            ]);
        });

        return back()->with(
            'success',
            'La cita se rechazó correctamente.'
        );
    }

    /**
     * Marcar una cita confirmada como atendida.
     */
    public function attend(
        Request $request,
        Appointment $appointment
    ): RedirectResponse {
        $this->authorizeManager($request);

        $validated = $request->validate([
            'attention_notes' => [
                'nullable',
                'string',
                'max:2000',
            ],
        ]);

        DB::transaction(function () use (
            $request,
            $appointment,
            $validated
        ) {
            $current = Appointment::query()
                ->findOrFail($appointment->id);

            if ($current->status !== 'confirmada') {
                throw ValidationException::withMessages([
                    'appointment' =>
                        'Solo puedes atender citas confirmadas.',
                ]);
            }

            $start = Carbon::parse(
                $current->appointment_date->format('Y-m-d')
                . ' ' . $current->start_time
            );

            if ($start->greaterThan(now())) {
                throw ValidationException::withMessages([
                    'appointment' =>
                        'No puedes marcar como atendida una cita que aún no comienza.',
                ]);
            }

            $current->update([
                'status' => 'atendida',
                'managed_by' => $request->user()->id,
                'attended_at' => now(),
                'attention_notes' =>
                    $validated['attention_notes'] ?? null,
            ]);
        });

        return back()->with(
            'success',
            'La cita se marcó como atendida.'
        );
    }

    /**
     * Cancelar una cita desde el panel del encargado.
     */
    public function managerCancel(
        Request $request,
        Appointment $appointment
    ): RedirectResponse {
        $this->authorizeManager($request);

        DB::transaction(function () use (
            $request,
            $appointment
        ) {
            $current = Appointment::query()
                ->findOrFail($appointment->id);

            if (!in_array(
                $current->status,
                ['pendiente', 'confirmada'],
                true
            )) {
                throw ValidationException::withMessages([
                    'appointment' =>
                        'Esta cita ya no puede cancelarse.',
                ]);
            }

            $current->update([
                'status' => 'cancelada',
                'managed_by' => $request->user()->id,
                'cancelled_at' => now(),
            ]);
        });

        return back()->with(
            'success',
            'La cita se canceló correctamente.'
        );
    }

    /**
     * Validar fecha, horario e intervalo de atención.
     */
    private function validateSchedule(
        Carbon $date,
        Carbon $start,
        Carbon $end
    ): void {
        $schedule = Schedule::query()
            ->where('day_of_week', $date->dayOfWeek)
            ->first();

        if (!$schedule || !$schedule->is_active) {
            throw ValidationException::withMessages([
                'appointment_date' =>
                    'El establecimiento no atiende en la fecha seleccionada.',
            ]);
        }

        if ($start->lessThanOrEqualTo(now())) {
            throw ValidationException::withMessages([
                'start_time' =>
                    'Debes seleccionar un horario futuro.',
            ]);
        }

        $opening = Carbon::parse(
            $date->format('Y-m-d') .
            ' ' . $schedule->opening_time
        );

        $closing = Carbon::parse(
            $date->format('Y-m-d') .
            ' ' . $schedule->closing_time
        );

        if (
            $start->lessThan($opening) ||
            $end->greaterThan($closing) ||
            $end->lessThanOrEqualTo($start)
        ) {
            throw ValidationException::withMessages([
                'start_time' =>
                    'La cita debe comenzar y terminar dentro del horario de atención.',
            ]);
        }

        $minutes = $opening->diffInMinutes($start);

        if ($minutes % $schedule->slot_minutes !== 0) {
            throw ValidationException::withMessages([
                'start_time' =>
                    'La hora seleccionada no coincide con los intervalos disponibles.',
            ]);
        }
    }

    /**
     * Estados válidos de una cita.
     */
    private function statuses(): array
    {
        return [
            'pendiente',
            'confirmada',
            'rechazada',
            'cancelada',
            'atendida',
        ];
    }

    /**
     * Comprobar que el usuario sea cliente.
     */
    private function authorizeClient(Request $request): void
    {
        $user = $request->user();

        abort_unless(
            $user !== null
                && $user->is_active
                && $user->role === 'cliente',
            403,
            'No tienes permiso para acceder a este apartado.'
        );
    }

    /**
     * Comprobar que el usuario sea encargado.
     */
    private function authorizeManager(Request $request): void
    {
        $user = $request->user();

        abort_unless(
            $user !== null
                && $user->is_active
                && $user->role === 'encargado',
            403,
            'No tienes permiso para administrar citas.'
        );
    }

    /**
     * Verificar que la cita pertenezca al cliente.
     */
    private function authorizeOwner(
        Request $request,
        Appointment $appointment
    ): void {
        abort_unless(
            $appointment->user_id === $request->user()->id,
            403,
            'No tienes permiso para consultar esta cita.'
        );
    }
}
