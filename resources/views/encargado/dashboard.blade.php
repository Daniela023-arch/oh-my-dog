
@extends('layouts.app')

@section('title', 'Dashboard del encargado | Oh My Dog!')

@section('page-title', 'Panel del encargado')

@section('page-subtitle', 'Administración general de la estética canina')

@section('heading', 'Resumen del negocio')

@section('heading-description', 'Consulta las estadísticas, solicitudes y próximas citas de Oh My Dog!.')

@section('page-actions')

    <a href="{{ route('encargado.citas.index') }}"
       class="btn btn-primary">

        Ver todas las citas

    </a>

@endsection

@section('content')

{{-- ==========================================
     MENSAJE DE BIENVENIDA
========================================== --}}

<div class="card mb-4">

    <div class="card-body">

        <span class="eyebrow">
            PANEL DE ADMINISTRACIÓN
        </span>

        <h2 class="mt-3">
            Bienvenido, {{ $user->name }}
        </h2>

        <p class="text-muted mt-2">

            Desde este panel puedes revisar las
            solicitudes de citas, administrar los
            servicios y mantener actualizados
            los horarios de atención.

        </p>

    </div>

</div>

{{-- ==========================================
     ESTADÍSTICAS PRINCIPALES
========================================== --}}

<div class="stats-grid">

    {{-- CLIENTES --}}

    <div class="stat-card">

        <div class="stat-top">

            <span class="stat-label">
                Clientes registrados
            </span>

            <div class="stat-icon">

                <svg class="icon"
                     viewBox="0 0 24 24"
                     aria-hidden="true">

                    <circle cx="12" cy="8" r="4"></circle>

                    <path d="M4 21v-2
                             a8 8 0 0 1 16 0v2">
                    </path>

                </svg>

            </div>

        </div>

        <div class="stat-value">
            {{ number_format($totalClients) }}
        </div>

        <p class="text-muted">
            {{ number_format($activeClients) }}
            clientes activos
        </p>

    </div>

    {{-- MASCOTAS --}}

    <div class="stat-card">

        <div class="stat-top">

            <span class="stat-label">
                Mascotas registradas
            </span>

            <div class="stat-icon">

                <svg class="icon"
                     viewBox="0 0 24 24"
                     aria-hidden="true">

                    <circle cx="7.5" cy="8" r="1.2"></circle>
                    <circle cx="12" cy="5.5" r="1.2"></circle>
                    <circle cx="16.5" cy="8" r="1.2"></circle>

                    <path d="M12 12
                             C9 12 7 15 7 17
                             C7 19 9 20 12 19
                             C15 20 17 19 17 17
                             C17 15 15 12 12 12Z">
                    </path>

                </svg>

            </div>

        </div>

        <div class="stat-value">
            {{ number_format($totalPets) }}
        </div>

        <p class="text-muted">
            Mascotas de los clientes
        </p>

    </div>

    {{-- SERVICIOS --}}

    <div class="stat-card">

        <div class="stat-top">

            <span class="stat-label">
                Servicios disponibles
            </span>

            <div class="stat-icon">

                <svg class="icon"
                     viewBox="0 0 24 24"
                     aria-hidden="true">

                    <path d="M20 4L4 20
                             M7 4l13 13">
                    </path>

                    <circle cx="5" cy="5" r="2"></circle>
                    <circle cx="19" cy="19" r="2"></circle>

                </svg>

            </div>

        </div>

        <div class="stat-value">
            {{ number_format($totalServices) }}
        </div>

        <p class="text-muted">
            Servicios activos del catálogo
        </p>

    </div>

    {{-- CITAS TOTALES --}}

    <div class="stat-card">

        <div class="stat-top">

            <span class="stat-label">
                Citas registradas
            </span>

            <div class="stat-icon">

                <svg class="icon"
                     viewBox="0 0 24 24"
                     aria-hidden="true">

                    <rect x="3" y="5"
                          width="18" height="16"
                          rx="2"></rect>

                    <path d="M8 3v4
                             M16 3v4
                             M3 10h18">
                    </path>

                </svg>

            </div>

        </div>

        <div class="stat-value">
            {{ number_format($totalAppointments) }}
        </div>

        <p class="text-muted">
            Historial general de citas
        </p>

    </div>

</div>

{{-- ==========================================
     ESTADOS DE LAS CITAS
========================================== --}}

<div class="stats-grid mt-4">

    <div class="stat-card">

        <div class="stat-top">

            <span class="stat-label">
                Solicitudes pendientes
            </span>

            <span class="badge badge-warning">
                Pendientes
            </span>

        </div>

        <div class="stat-value">
            {{ number_format($pendingAppointments) }}
        </div>

        <a href="{{ route('encargado.citas.index', ['status' => 'pendiente']) }}"
           class="btn btn-secondary btn-sm mt-3">
            Revisar solicitudes
        </a>

    </div>

    <div class="stat-card">

        <div class="stat-top">

            <span class="stat-label">
                Citas confirmadas
            </span>

            <span class="badge badge-success">
                Confirmadas
            </span>

        </div>

        <div class="stat-value">
            {{ number_format($confirmedAppointments) }}
        </div>

    </div>

    <div class="stat-card">

        <div class="stat-top">

            <span class="stat-label">
                Citas atendidas
            </span>

            <span class="badge badge-info">
                Atendidas
            </span>

        </div>

        <div class="stat-value">
            {{ number_format($attendedAppointments) }}
        </div>

    </div>

    <div class="stat-card">

        <div class="stat-top">

            <span class="stat-label">
                Citas de hoy
            </span>

            <span class="badge badge-neutral">
                Hoy
            </span>

        </div>

        <div class="stat-value">
            {{ number_format($todayAppointments->count()) }}
        </div>

    </div>

</div>

{{-- ==========================================
     SOLICITUDES PENDIENTES
========================================== --}}

<div class="card mt-4">

    <div class="card-header">

        <div>

            <h3>
                Solicitudes pendientes
            </h3>

            <p class="text-muted">
                Citas que necesitan revisión
                del encargado.
            </p>

        </div>

        <a href="{{ route('encargado.citas.index', ['status' => 'pendiente']) }}"
           class="btn btn-secondary btn-sm">

            Ver todas

        </a>

    </div>

    @if($pendingRequests->isNotEmpty())

        <div class="table-responsive">

            <table class="table">

                <thead>

                    <tr>
                        <th>Cliente</th>
                        <th>Mascota</th>
                        <th>Fecha</th>
                        <th>Horario</th>
                        <th>Estado</th>
                        <th>Acción</th>
                    </tr>

                </thead>

                <tbody>

                    @foreach($pendingRequests as $appointment)

                        <tr>

                            <td>

                                <span class="table-primary">
                                    {{ $appointment->user?->name ?? 'Cliente no disponible' }}
                                </span>

                            </td>

                            <td>

                                {{ $appointment->pet?->name ?? 'Mascota no disponible' }}

                            </td>

                            <td>

                                {{ $appointment->appointment_date->format('d/m/Y') }}

                            </td>

                            <td>

                                {{ substr($appointment->start_time, 0, 5) }}

                                -

                                {{ substr($appointment->end_time, 0, 5) }}

                            </td>

                            <td>

                                <span class="badge badge-warning">
                                    Pendiente
                                </span>

                            </td>

                            <td>

                                <a
                                    href="{{ route('encargado.citas.show', $appointment) }}"
                                    class="btn btn-secondary btn-sm"
                                >
                                    Revisar
                                </a>

                            </td>

                        </tr>

                    @endforeach

                </tbody>

            </table>

        </div>

    @else

        <div class="empty-state">

            <h3>
                No hay solicitudes pendientes
            </h3>

            <p>
                Todas las solicitudes están
                revisadas por el momento.
            </p>

        </div>

    @endif

</div>

{{-- ==========================================
     PRÓXIMAS CITAS
========================================== --}}

<div class="card mt-4">

    <div class="card-header">

        <div>

            <h3>
                Próximas citas
            </h3>

            <p class="text-muted">
                Citas confirmadas que están
                programadas próximamente.
            </p>

        </div>

        <a href="{{ route('encargado.citas.index') }}"
           class="btn btn-secondary btn-sm">

            Ver agenda

        </a>

    </div>

    @if($upcomingAppointments->isNotEmpty())

        <div class="table-responsive">

            <table class="table">

                <thead>

                    <tr>
                        <th>Cliente</th>
                        <th>Mascota</th>
                        <th>Fecha</th>
                        <th>Horario</th>
                        <th>Estado</th>
                        <th>Acción</th>
                    </tr>

                </thead>

                <tbody>

                    @foreach($upcomingAppointments as $appointment)

                        <tr>

                            <td>

                                <span class="table-primary">
                                    {{ $appointment->user?->name ?? 'Cliente no disponible' }}
                                </span>

                            </td>

                            <td>

                                {{ $appointment->pet?->name ?? 'Mascota no disponible' }}

                            </td>

                            <td>

                                {{ $appointment->appointment_date->format('d/m/Y') }}

                            </td>

                            <td>

                                {{ substr($appointment->start_time, 0, 5) }}

                                -

                                {{ substr($appointment->end_time, 0, 5) }}

                            </td>

                            <td>

                                <span class="badge badge-success">
                                    Confirmada
                                </span>

                            </td>

                            <td>

                                <a
                                    href="{{ route('encargado.citas.show', $appointment) }}"
                                    class="btn btn-secondary btn-sm"
                                >
                                    Ver detalle
                                </a>

                            </td>

                        </tr>

                    @endforeach

                </tbody>

            </table>

        </div>

    @else

        <div class="empty-state">

            <h3>
                No hay próximas citas
            </h3>

            <p>
                Todavía no hay citas confirmadas
                para los próximos días.
            </p>

        </div>

    @endif

</div>

{{-- ==========================================
     RESUMEN ADICIONAL
========================================== --}}

<div class="dashboard-grid mt-4">

    {{-- ESTADOS ADICIONALES --}}

    <div class="card">

        <div class="card-header">

            <h3>
                Resumen de citas
            </h3>

        </div>

        <div class="card-body">

            <div class="list-item">

                <span>Solicitudes rechazadas</span>

                <span class="badge badge-danger">
                    {{ number_format($rejectedAppointments) }}
                </span>

            </div>

            <div class="list-item">

                <span>Citas canceladas</span>

                <span class="badge badge-neutral">
                    {{ number_format($cancelledAppointments) }}
                </span>

            </div>

            <div class="list-item">

                <span>Citas de los últimos 7 días</span>

                <span class="badge badge-info">
                    {{ number_format($appointmentsLast7Days) }}
                </span>

            </div>

        </div>

    </div>

    {{-- ACCESOS RÁPIDOS --}}

    <div class="card">

        <div class="card-header">

            <h3>
                Accesos rápidos
            </h3>

        </div>

        <div class="card-body">

            <div class="form-actions">

                <a href="{{ route('encargado.citas.index') }}"
                   class="btn btn-primary btn-block">
                    Gestionar citas
                </a>

                <a href="{{ route('encargado.servicios.index') }}"
                   class="btn btn-secondary btn-block">
                    Administrar servicios
                </a>

                <a href="{{ route('encargado.horarios.index') }}"
                   class="btn btn-secondary btn-block">
                    Configurar horarios
                </a>

                <a href="{{ route('home') }}"
                   class="btn btn-outline btn-block">
                    Ver sitio público
                </a>

            </div>

        </div>

    </div>

</div>

@endsection
