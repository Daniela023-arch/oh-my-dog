
@extends('layouts.app')

@section('title', 'Mis citas | Oh My Dog!')

@section('page-title', 'Mis citas')

@section('page-subtitle', 'Consulta y administra tus solicitudes')

@section('heading', 'Mis citas')

@section('heading-description', 'Revisa las fechas, servicios y estados de tus citas de estética canina.')

@section('page-actions')

    <a href="{{ route('cliente.citas.create') }}"
       class="btn btn-primary">

        <svg class="icon"
             viewBox="0 0 24 24"
             aria-hidden="true">

            <path d="M12 5v14 M5 12h14"></path>

        </svg>

        Solicitar nueva cita

    </a>

@endsection

@section('content')

{{-- ==========================================
     PRESENTACIÓN
========================================== --}}

<div class="card mb-4">

    <div class="card-body">

        <span class="eyebrow">
            MI AGENDA
        </span>

        <h2 class="mt-3">
            Historial de mis citas
        </h2>

        <p class="text-muted mt-2">
            Consulta las solicitudes que has
            realizado para tus mascotas.

            Aquí podrás conocer cuáles están
            pendientes de revisión, confirmadas,
            rechazadas, canceladas o atendidas.
        </p>

    </div>

</div>

{{-- ==========================================
     ESTADOS DE LAS CITAS
========================================== --}}

<div class="card mb-4">

    <div class="card-body">

        <div class="form-actions">

            <a href="{{ route('cliente.citas.index') }}"
               class="btn {{ !request('status') ? 'btn-primary' : 'btn-secondary' }} btn-sm">
                Todas
            </a>

            <a href="{{ route('cliente.citas.index', ['status' => 'pendiente']) }}"
               class="btn {{ request('status') === 'pendiente' ? 'btn-primary' : 'btn-secondary' }} btn-sm">
                Pendientes
            </a>

            <a href="{{ route('cliente.citas.index', ['status' => 'confirmada']) }}"
               class="btn {{ request('status') === 'confirmada' ? 'btn-primary' : 'btn-secondary' }} btn-sm">
                Confirmadas
            </a>

            <a href="{{ route('cliente.citas.index', ['status' => 'atendida']) }}"
               class="btn {{ request('status') === 'atendida' ? 'btn-primary' : 'btn-secondary' }} btn-sm">
                Atendidas
            </a>

            <a href="{{ route('cliente.citas.index', ['status' => 'rechazada']) }}"
               class="btn {{ request('status') === 'rechazada' ? 'btn-primary' : 'btn-secondary' }} btn-sm">
                Rechazadas
            </a>

            <a href="{{ route('cliente.citas.index', ['status' => 'cancelada']) }}"
               class="btn {{ request('status') === 'cancelada' ? 'btn-primary' : 'btn-secondary' }} btn-sm">
                Canceladas
            </a>

        </div>

    </div>

</div>

{{-- ==========================================
     LISTADO DE CITAS
========================================== --}}

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

    /*
     * El controlador entrega las citas del cliente.
     * El filtro se aplica a los registros recibidos.
     */

    $selectedStatus = request('status');

    $validStatuses = array_keys($statusLabels);

    $filteredAppointments = $appointments;

    if (in_array($selectedStatus, $validStatuses, true)) {

        $filteredAppointments = $appointments->filter(
            function ($appointment) use ($selectedStatus) {
                return $appointment->status === $selectedStatus;
            }
        );

    }

@endphp

<div class="card">

    <div class="card-header">

        <div>

            <h3>
                Citas registradas
            </h3>

            <p class="text-muted">
                Selecciona una cita para consultar
                todos sus detalles.
            </p>

        </div>

        <span class="badge badge-info">
            {{ $filteredAppointments->count() }}
            registros
        </span>

    </div>

    @if($filteredAppointments->isNotEmpty())

        <div class="table-responsive">

            <table class="table">

                <thead>

                    <tr>
                        <th>Mascota</th>
                        <th>Fecha</th>
                        <th>Horario</th>
                        <th>Servicios</th>
                        <th>Total</th>
                        <th>Estado</th>
                        <th>Acciones</th>
                    </tr>

                </thead>

                <tbody>

                    @foreach($filteredAppointments as $appointment)

                        <tr>

                            {{-- MASCOTA --}}

                            <td>

                                <span class="table-primary">
                                    {{ $appointment->pet?->name ?? 'Mascota no disponible' }}
                                </span>

                                <div class="table-secondary">
                                    {{ $appointment->pet?->breed ?: ($appointment->pet?->species ?? '') }}
                                </div>

                            </td>

                            {{-- FECHA --}}

                            <td>

                                {{ $appointment->appointment_date->format('d/m/Y') }}

                            </td>

                            {{-- HORARIO --}}

                            <td>

                                {{ substr($appointment->start_time, 0, 5) }}

                                -

                                {{ substr($appointment->end_time, 0, 5) }}

                            </td>

                            {{-- SERVICIOS --}}

                            <td>

                                @forelse($appointment->services as $service)

                                    <div class="table-secondary">

                                        {{ $service->name }}

                                    </div>

                                @empty

                                    <span class="text-muted">
                                        Sin servicios
                                    </span>

                                @endforelse

                            </td>

                            {{-- PRECIO --}}

                            <td>

                                <strong>
                                    ${{ number_format(
                                        (float) $appointment->services->sum('pivot.price'),
                                        2
                                    ) }}
                                </strong>

                            </td>

                            {{-- ESTADO --}}

                            <td>

                                <span class="badge {{ $statusClasses[$appointment->status] ?? 'badge-neutral' }}">

                                    {{ $statusLabels[$appointment->status] ?? ucfirst($appointment->status) }}

                                </span>

                            </td>

                            {{-- ACCIONES --}}

                            <td>

                                <div class="table-actions">

                                    <a
                                        href="{{ route('cliente.citas.show', $appointment) }}"
                                        class="btn btn-secondary btn-sm"
                                    >
                                        Ver detalle
                                    </a>

                                    @if(
                                        in_array(
                                            $appointment->status,
                                            ['pendiente', 'confirmada'],
                                            true
                                        )
                                        && \Carbon\Carbon::parse(
                                            $appointment->appointment_date->format('Y-m-d')
                                            . ' '
                                            . $appointment->start_time
                                        )->isFuture()
                                    )

                                        <form
                                            action="{{ route('cliente.citas.cancel', $appointment) }}"
                                            method="POST"
                                            data-confirm="¿Seguro que deseas cancelar esta cita?"
                                        >

                                            @csrf
                                            @method('PATCH')

                                            <button
                                                type="submit"
                                                class="btn btn-danger btn-sm"
                                            >
                                                Cancelar
                                            </button>

                                        </form>

                                    @endif

                                </div>

                            </td>

                        </tr>

                    @endforeach

                </tbody>

            </table>

        </div>

    @else

        {{-- ==================================
             SIN CITAS
        ================================== --}}

        <div class="empty-state">

            <h3>
                No hay citas para mostrar
            </h3>

            <p>

                @if($selectedStatus)

                    No tienes citas registradas
                    con el estado seleccionado.

                @else

                    Todavía no has solicitado
                    ninguna cita para tus mascotas.

                @endif

            </p>

            <a href="{{ route('cliente.citas.create') }}"
               class="btn btn-primary mt-4">

                Solicitar nueva cita

            </a>

        </div>

    @endif

</div>

{{-- ==========================================
     INFORMACIÓN DE LOS ESTADOS
========================================== --}}

<div class="card mt-4">

    <div class="card-header">

        <h3>
            ¿Qué significa cada estado?
        </h3>

    </div>

    <div class="card-body">

        <div class="detail-grid">

            <div class="detail-item">

                <span class="badge badge-warning">
                    Pendiente
                </span>

                <p class="text-muted mt-2">
                    Tu solicitud fue registrada
                    y espera la revisión del encargado.
                </p>

            </div>

            <div class="detail-item">

                <span class="badge badge-success">
                    Confirmada
                </span>

                <p class="text-muted mt-2">
                    El encargado aprobó la cita.
                    Puedes acudir en la fecha
                    y horario indicados.
                </p>

            </div>

            <div class="detail-item">

                <span class="badge badge-info">
                    Atendida
                </span>

                <p class="text-muted mt-2">
                    El servicio de estética
                    ya fue realizado.
                </p>

            </div>

            <div class="detail-item">

                <span class="badge badge-danger">
                    Rechazada
                </span>

                <p class="text-muted mt-2">
                    El encargado no pudo aprobar
                    la solicitud. Consulta
                    el detalle para conocer el motivo.
                </p>

            </div>

            <div class="detail-item">

                <span class="badge badge-neutral">
                    Cancelada
                </span>

                <p class="text-muted mt-2">
                    La cita fue cancelada
                    y ya no está programada
                    para atención.
                </p>

            </div>

        </div>

    </div>

</div>

{{-- ==========================================
     ACCESO RÁPIDO
========================================== --}}

<div class="card mt-4">

    <div class="card-body">

        <div class="dashboard-grid">

            <div>

                <h3>
                    ¿Necesitas otra cita?
                </h3>

                <p class="text-muted mt-3">
                    Selecciona una de tus mascotas,
                    elige los servicios que necesita
                    y consulta los horarios disponibles.
                </p>

            </div>

            <div class="form-actions">

                <a href="{{ route('cliente.citas.create') }}"
                   class="btn btn-primary">
                    Solicitar cita
                </a>

                <a href="{{ route('cliente.mascotas.index') }}"
                   class="btn btn-secondary">
                    Mis mascotas
                </a>

            </div>

        </div>

    </div>

</div>

@endsection
