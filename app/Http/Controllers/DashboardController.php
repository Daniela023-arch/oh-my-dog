<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\Pet;
use App\Models\Service;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    /**
     * Dashboard del cliente.
     */
    public function client(Request $request): View
    {
        $this->authorizeClient($request);

        $user = $request->user();

        // Estadísticas de mascotas.
        $totalPets = $user->pets()->count();

        $activePets = $user->pets()
            ->where('is_active', true)
            ->count();

        // Estadísticas de citas del cliente.
        $totalAppointments = $user->appointments()
            ->count();

        $pendingAppointments = $user->appointments()
            ->where('status', 'pendiente')
            ->count();

        $confirmedAppointments = $user->appointments()
            ->where('status', 'confirmada')
            ->count();

        $attendedAppointments = $user->appointments()
            ->where('status', 'atendida')
            ->count();

        // Próximas citas confirmadas.
        $upcomingAppointments = $user->appointments()
            ->with(['pet', 'services'])
            ->where('status', 'confirmada')
            ->where(function ($query) {
                $query->whereDate(
                    'appointment_date',
                    '>',
                    today()->toDateString()
                )->orWhere(function ($sameDay) {
                    $sameDay->whereDate(
                        'appointment_date',
                        today()->toDateString()
                    )->where(
                        'end_time',
                        '>',
                        now()->format('H:i:s')
                    );
                });
            })
            ->orderBy('appointment_date')
            ->orderBy('start_time')
            ->limit(5)
            ->get();

        // Últimas solicitudes del cliente.
        $recentAppointments = $user->appointments()
            ->with(['pet', 'services'])
            ->orderByDesc('created_at')
            ->limit(5)
            ->get();

        // Mascotas activas para acceso rápido.
        $pets = $user->pets()
            ->where('is_active', true)
            ->orderBy('name')
            ->limit(6)
            ->get();

        return view('cliente.dashboard', compact(
            'user',
            'totalPets',
            'activePets',
            'totalAppointments',
            'pendingAppointments',
            'confirmedAppointments',
            'attendedAppointments',
            'upcomingAppointments',
            'recentAppointments',
            'pets'
        ));
    }

    /**
     * Dashboard del encargado.
     */
    public function manager(Request $request): View
    {
        $this->authorizeManager($request);

        $user = $request->user();

        // Estadísticas generales.
        $totalClients = User::query()
            ->where('role', 'cliente')
            ->count();

        $activeClients = User::query()
            ->where('role', 'cliente')
            ->where('is_active', true)
            ->count();

        $totalPets = Pet::count();

        $totalServices = Service::query()
            ->where('is_active', true)
            ->count();

        // Estadísticas de citas.
        $totalAppointments = Appointment::count();

        $pendingAppointments = Appointment::query()
            ->where('status', 'pendiente')
            ->count();

        $confirmedAppointments = Appointment::query()
            ->where('status', 'confirmada')
            ->count();

        $attendedAppointments = Appointment::query()
            ->where('status', 'atendida')
            ->count();

        $rejectedAppointments = Appointment::query()
            ->where('status', 'rechazada')
            ->count();

        $cancelledAppointments = Appointment::query()
            ->where('status', 'cancelada')
            ->count();

        // Citas programadas para hoy.
        $todayAppointments = Appointment::query()
            ->with(['user', 'pet', 'services'])
            ->whereDate(
                'appointment_date',
                today()->toDateString()
            )
            ->whereIn('status', [
                'pendiente',
                'confirmada',
            ])
            ->orderBy('start_time')
            ->get();

        // Solicitudes pendientes de revisión.
        $pendingRequests = Appointment::query()
            ->with(['user', 'pet', 'services'])
            ->where('status', 'pendiente')
            ->orderBy('appointment_date')
            ->orderBy('start_time')
            ->limit(8)
            ->get();

        // Próximas citas confirmadas.
        $upcomingAppointments = Appointment::query()
            ->with(['user', 'pet', 'services'])
            ->where('status', 'confirmada')
            ->where(function ($query) {
                $query->whereDate(
                    'appointment_date',
                    '>',
                    today()->toDateString()
                )->orWhere(function ($sameDay) {
                    $sameDay->whereDate(
                        'appointment_date',
                        today()->toDateString()
                    )->where(
                        'end_time',
                        '>',
                        now()->format('H:i:s')
                    );
                });
            })
            ->orderBy('appointment_date')
            ->orderBy('start_time')
            ->limit(8)
            ->get();

        // Cantidad de citas registradas en los últimos 7 días.
        $appointmentsLast7Days = Appointment::query()
            ->whereDate(
                'created_at',
                '>=',
                today()->subDays(6)->toDateString()
            )
            ->count();

        return view('encargado.dashboard', compact(
            'user',
            'totalClients',
            'activeClients',
            'totalPets',
            'totalServices',
            'totalAppointments',
            'pendingAppointments',
            'confirmedAppointments',
            'attendedAppointments',
            'rejectedAppointments',
            'cancelledAppointments',
            'todayAppointments',
            'pendingRequests',
            'upcomingAppointments',
            'appointmentsLast7Days'
        ));
    }

    /**
     * Verificar acceso del cliente.
     */
    private function authorizeClient(Request $request): void
    {
        $user = $request->user();

        abort_unless(
            $user !== null
                && $user->is_active
                && $user->role === 'cliente',
            403,
            'No tienes permiso para acceder al panel de cliente.'
        );
    }

    /**
     * Verificar acceso del encargado.
     */
    private function authorizeManager(Request $request): void
    {
        $user = $request->user();

        abort_unless(
            $user !== null
                && $user->is_active
                && $user->role === 'encargado',
            403,
            'No tienes permiso para acceder al panel del encargado.'
        );
    }
}
