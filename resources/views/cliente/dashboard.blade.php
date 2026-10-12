
@extends('layouts.app')

@section('title', 'Mi dashboard | Oh My Dog!')

@section('page-title', 'Panel del cliente')

@section('page-subtitle', 'Gestiona tus mascotas y citas')

@section('heading', 'Mi panel principal')

@section('heading-description', 'Consulta tus mascotas, solicitudes y próximas citas.')

@section('page-actions')
    <a href="{{ route('cliente.citas.create') }}"
       class="btn btn-primary">
        Solicitar cita
    </a>
@endsection

@section('content')

{{-- ==========================================
     BIENVENIDA
========================================== --}}

<div class="card mb-4">
    <div class="card-body">

        <span class="eyebrow">
            MI CUENTA
        </span>

        <h2 class="mt-3">
            Bienvenido, {{ $user->name }}
        </h2>

        <p class="text-muted mt-2">
            Desde aquí puedes administrar tus
            mascotas, solicitar servicios de
            estética canina y consultar el
            estado de tus citas.
        </p>

        <div class="form-actions mt-4">

            <a href="{{ route('cliente.mascotas.create') }}"
               class="btn btn-secondary">
                Registrar mascota
            </a>

            <a href="{{ route('cliente.citas.create') }}"
               class="btn btn-primary">
                Nueva cita
            </a>

        </div>

    </div>
</div>

{{-- ==========================================
     ESTADÍSTICAS PRINCIPALES
========================================== --}}

<div class="stats-grid">

    {{-- MASCOTAS --}}

    <div class="stat-card">

        <div class="stat-top">

            <span class="stat-label">
                Mis mascotas
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
            {{ number_format($activePets) }}
            mascotas activas
        </p>

    </div>

    {{-- CITAS REGISTRADAS --}}

    <div class="stat-card">

        <div class="stat-top">

            <span class="stat-label">
                Mis citas
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
            Historial de solicitudes
        </p>

    </div>

    {{-- CITAS PENDIENTES --}}

    <div class="stat-card">

        <div class="stat-top">

            <span class="stat-label">
                Citas pendientes
            </span>

            <span class="badge badge-warning">
                Pendientes
            </span>

        </div>

        <div class="stat-value">
            {{ number_format($pendingAppointments) }}
        </div>

        <p class="text-muted">
            Esperando confirmación
        </p>

    </div>

    {{-- CITAS CONFIRMADAS --}}

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

        <p class="text-muted">
            Citas aprobadas
        </p>

    </div>

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
                Tus próximas citas confirmadas.
            </p>
        </div>

        <a href="{{ route('cliente.citas.index') }}"
           class="btn btn-secondary btn-sm">
            Ver todas
        </a>

    </div>

    @if($upcomingAppointments->isNotEmpty())

        <div class="table-responsive">

            <table class="table">

                <thead>
                    <tr>
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
                                    {{ $appointment->pet?->name ?? 'Mascota no disponible' }}
                                </span>
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
                                <a href="{{ route('cliente.citas.show', $appointment) }}"
                                   class="btn btn-secondary btn-sm">
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
                No tienes próximas citas
            </h3>

            <p>
                Cuando tengas una cita confirmada,
                aparecerá en este apartado.
            </p>

            <a href="{{ route('cliente.citas.create') }}"
               class="btn btn-primary">
                Solicitar una cita
            </a>

        </div>

    @endif

</div>

{{-- ==========================================
     MIS MASCOTAS
========================================== --}}

<div class="card mt-4">

    <div class="card-header">

        <div>
            <h3>
                Mis mascotas
            </h3>

            <p class="text-muted">
                Mascotas registradas en tu cuenta.
            </p>
        </div>

        <a href="{{ route('cliente.mascotas.index') }}"
           class="btn btn-secondary btn-sm">
            Ver mascotas
        </a>

    </div>

    <div class="card-body">

        @if($pets->isNotEmpty())

            <div class="pet-grid">

                @foreach($pets as $pet)

                    <article class="pet-card">

                        <div class="pet-avatar">

                            <svg class="icon icon-lg"
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

                        <h3 class="pet-name">
                            {{ $pet->name }}
                        </h3>

                        <p class="pet-meta">
                            {{ $pet->breed ?: $pet->species }}
                        </p>

                        @if($pet->is_active)

                            <span class="badge badge-success">
                                Activa
                            </span>

                        @else

                            <span class="badge badge-neutral">
                                Inactiva
                            </span>

                        @endif

                        <div class="pet-actions">

                            <a href="{{ route('cliente.mascotas.show', $pet) }}"
                               class="btn btn-secondary btn-sm">
                                Ver mascota
                            </a>

                        </div>

                    </article>

                @endforeach

            </div>

        @else

            <div class="empty-state">

                <h3>
                    Todavía no tienes mascotas registradas
                </h3>

                <p>
                    Registra a tu mascota para
                    comenzar a solicitar citas.
                </p>

                <a href="{{ route('cliente.mascotas.create') }}"
                   class="btn btn-primary">
                    Registrar mascota
                </a>

            </div>

        @endif

    </div>

</div>

{{-- ==========================================
     HISTORIAL RECIENTE
========================================== --}}

<div class="card mt-4">

    <div class="card-header">

        <div>
            <h3>
                Actividad reciente
            </h3>

            <p class="text-muted">
                Tus últimas solicitudes de cita.
            </p>
        </div>

        <a href="{{ route('cliente.citas.index') }}"
           class="btn btn-secondary btn-sm">
            Historial completo
        </a>

    </div>

    @if($recentAppointments->isNotEmpty())

        <div class="table-responsive">

            <table class="table">

                <thead>
                    <tr>
                        <th>Mascota</th>
                        <th>Fecha</th>
                        <th>Estado</th>
                        <th>Acción</th>
                    </tr>
                </thead>

                <tbody>

                    @foreach($recentAppointments as $appointment)

                        @php
                            $statusLabels = [
                                'pendiente' => 'Pendiente',
                                'confirmada' => 'Confirmada',
                                'rechazada' => 'Rechazada',
                                'cancelada' => 'Cancelada',
                                'atendida' => 'Atendida',
                            ];

                            $statusClasses = [
                                'pendiente' => 'badge-warning',
                                'confirmada' => 'badge-success',
                                'rechazada' => 'badge-danger',
                                'cancelada' => 'badge-neutral',
                                'atendida' => 'badge-info',
                            ];
                        @endphp

                        <tr>

                            <td>
                                {{ $appointment->pet?->name ?? 'Mascota no disponible' }}
                            </td>

                            <td>
                                {{ $appointment->appointment_date->format('d/m/Y') }}
                            </td>

                            <td>
                                <span class="badge {{ $statusClasses[$appointment->status] ?? 'badge-neutral' }}">
                                    {{ $statusLabels[$appointment->status] ?? ucfirst($appointment->status) }}
                                </span>
                            </td>

                            <td>
                                <a href="{{ route('cliente.citas.show', $appointment) }}"
                                   class="btn btn-secondary btn-sm">
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
                Aún no tienes citas registradas
            </h3>

            <p>
                Tus solicitudes aparecerán aquí
                cuando agendes tu primera cita.
            </p>

        </div>

    @endif

</div>

{{-- ==========================================
     RESUMEN Y ACCESOS RÁPIDOS
========================================== --}}

<div class="dashboard-grid mt-4">

    <div class="card">

        <div class="card-header">
            <h3>
                Resumen de mi cuenta
            </h3>
        </div>

        <div class="card-body">

            <div class="list-item">

                <span>
                    Mascotas activas
                </span>

                <span class="badge badge-success">
                    {{ number_format($activePets) }}
                </span>

            </div>

            <div class="list-item">

                <span>
                    Citas atendidas
                </span>

                <span class="badge badge-info">
                    {{ number_format($attendedAppointments) }}
                </span>

            </div>

            <div class="list-item">

                <span>
                    Citas pendientes
                </span>

                <span class="badge badge-warning">
                    {{ number_format($pendingAppointments) }}
                </span>

            </div>

        </div>

    </div>

    <div class="card">

        <div class="card-header">
            <h3>
                Accesos rápidos
            </h3>
        </div>

        <div class="card-body">

            <div class="form-actions">

                <a href="{{ route('cliente.mascotas.create') }}"
                   class="btn btn-primary btn-block">
                    Registrar mascota
                </a>

                <a href="{{ route('cliente.citas.create') }}"
                   class="btn btn-secondary btn-block">
                    Solicitar cita
                </a>

                <a href="{{ route('cliente.citas.index') }}"
                   class="btn btn-secondary btn-block">
                    Consultar mis citas
                </a>

                <a href="{{ route('servicios') }}"
                   class="btn btn-outline btn-block">
                    Ver servicios
                </a>

            </div>

        </div>

    </div>

</div>

@endsection
