
@extends('layouts.app')

@section('title', 'Detalle de cita | Oh My Dog!')

@section('page-title', 'Detalle de cita')

@section('page-subtitle', 'Información de tu solicitud')

@section('heading', 'Cita #' . $appointment->id)

@section('heading-description', 'Consulta el estado, los servicios y la información de tu cita.')

@section('page-actions')

    <a href="{{ route('cliente.citas.index') }}"
       class="btn btn-secondary">
        Volver a mis citas
    </a>

@endsection

@section('content')

@php
    /*
     * Información de presentación.
     * Los importes provienen de los valores
     * guardados al momento de solicitar la cita.
     */

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

    $statusDescriptions = [
        'pendiente' => 'Tu solicitud fue registrada y está esperando la revisión del encargado.',
        'confirmada' => 'El encargado aprobó tu solicitud. Tu cita está programada.',
        'rechazada' => 'El encargado no pudo aprobar esta solicitud.',
        'cancelada' => 'Esta cita fue cancelada y ya no está programada.',
        'atendida' => 'La cita fue atendida y los servicios quedaron registrados.',
    ];

    $services = $appointment->services;

    $totalPrice = $services->sum(function ($service) {
        return (float) $service->pivot->price;
    });

    $totalDuration = $services->sum(function ($service) {
        return (int) $service->pivot->duration_minutes;
    });

    $appointmentStart = \Carbon\Carbon::parse(
        $appointment->appointment_date->format('Y-m-d')
        . ' '
        . $appointment->start_time
    );

    $canCancel = in_array(
        $appointment->status,
        ['pendiente', 'confirmada'],
        true
    ) && $appointmentStart->isFuture();
@endphp

{{-- ==========================================
     ESTADO PRINCIPAL
========================================== --}}

<div class="card mb-4">

    <div class="card-body">

        <span class="eyebrow">
            SOLICITUD DE CITA
        </span>

        <h2 class="mt-3">
            Cita #{{ $appointment->id }}
        </h2>

        <div class="mt-3">

            <span class="badge {{ $statusClasses[$appointment->status] ?? 'badge-neutral' }}">

                {{ $statusLabels[$appointment->status] ?? ucfirst($appointment->status) }}

            </span>

        </div>

        <p class="text-muted mt-3">

            {{ $statusDescriptions[$appointment->status] ?? 'Consulta la información de tu cita.' }}

        </p>

    </div>

</div>

{{-- ==========================================
     INFORMACIÓN GENERAL
========================================== --}}

<div class="card mb-4">

    <div class="card-header">

        <h3>
            Información de la cita
        </h3>

    </div>

    <div class="card-body">

        <div class="detail-grid">

            <div class="detail-item">

                <span class="detail-label">
                    Número de cita
                </span>

                <strong class="detail-value">
                    #{{ $appointment->id }}
                </strong>

            </div>

            <div class="detail-item">

                <span class="detail-label">
                    Mascota
                </span>

                <strong class="detail-value">
                    {{ $appointment->pet?->name ?? 'No disponible' }}
                </strong>

            </div>

            <div class="detail-item">

                <span class="detail-label">
                    Fecha
                </span>

                <strong class="detail-value">
                    {{ $appointment->appointment_date->format('d/m/Y') }}
                </strong>

            </div>

            <div class="detail-item">

                <span class="detail-label">
                    Hora de inicio
                </span>

                <strong class="detail-value">
                    {{ substr($appointment->start_time, 0, 5) }}
                </strong>

            </div>

            <div class="detail-item">

                <span class="detail-label">
                    Hora de finalización
                </span>

                <strong class="detail-value">
                    {{ substr($appointment->end_time, 0, 5) }}
                </strong>

            </div>

            <div class="detail-item">

                <span class="detail-label">
                    Estado
                </span>

                <strong class="detail-value">
                    {{ $statusLabels[$appointment->status] ?? ucfirst($appointment->status) }}
                </strong>

            </div>

            <div class="detail-item">

                <span class="detail-label">
                    Fecha de solicitud
                </span>

                <strong class="detail-value">
                    {{ $appointment->created_at->format('d/m/Y H:i') }}
                </strong>

            </div>

            <div class="detail-item">

                <span class="detail-label">
                    Duración total
                </span>

                <strong class="detail-value">
                    {{ $totalDuration }} minutos
                </strong>

            </div>

        </div>

    </div>

</div>

{{-- ==========================================
     INFORMACIÓN DE LA MASCOTA
========================================== --}}

<div class="card mb-4">

    <div class="card-header">

        <h3>
            Mascota registrada
        </h3>

    </div>

    <div class="card-body">

        @if($appointment->pet)

            <div class="detail-grid">

                <div class="detail-item">

                    <span class="detail-label">
                        Nombre
                    </span>

                    <strong class="detail-value">
                        {{ $appointment->pet->name }}
                    </strong>

                </div>

                <div class="detail-item">

                    <span class="detail-label">
                        Especie
                    </span>

                    <strong class="detail-value">
                        {{ $appointment->pet->species }}
                    </strong>

                </div>

                <div class="detail-item">

                    <span class="detail-label">
                        Raza
                    </span>

                    <strong class="detail-value">
                        {{ $appointment->pet->breed ?: 'No registrada' }}
                    </strong>

                </div>

                <div class="detail-item">

                    <span class="detail-label">
                        Sexo
                    </span>

                    <strong class="detail-value">

                        @if($appointment->pet->sex === 'macho')
                            Macho
                        @elseif($appointment->pet->sex === 'hembra')
                            Hembra
                        @else
                            No especificado
                        @endif

                    </strong>

                </div>

            </div>

            <div class="mt-4">

                <a
                    href="{{ route('cliente.mascotas.show', $appointment->pet) }}"
                    class="btn btn-outline btn-sm"
                >
                    Ver perfil de mascota
                </a>

            </div>

        @else

            <p class="text-muted">
                La información de esta mascota
                no está disponible.
            </p>

        @endif

    </div>

</div>

{{-- ==========================================
     SERVICIOS SOLICITADOS
========================================== --}}

<div class="card mb-4">

    <div class="card-header">

        <div>

            <h3>
                Servicios solicitados
            </h3>

            <p class="text-muted">
                Desglose de los servicios incluidos
                en esta cita.
            </p>

        </div>

    </div>

    @if($services->isNotEmpty())

        <div class="table-responsive">

            <table class="table">

                <thead>

                    <tr>
                        <th>Servicio</th>
                        <th>Duración</th>
                        <th>Precio</th>
                    </tr>

                </thead>

                <tbody>

                    @foreach($services as $service)

                        <tr>

                            <td>

                                <span class="table-primary">
                                    {{ $service->name }}
                                </span>

                                @if($service->description)

                                    <div class="table-secondary">
                                        {{ $service->description }}
                                    </div>

                                @endif

                            </td>

                            <td>
                                {{ $service->pivot->duration_minutes }}
                                minutos
                            </td>

                            <td>

                                <strong>
                                    ${{ number_format(
                                        (float) $service->pivot->price,
                                        2
                                    ) }}
                                </strong>

                            </td>

                        </tr>

                    @endforeach

                </tbody>

            </table>

        </div>

        <div class="card-footer">

            <div class="detail-grid">

                <div class="detail-item">

                    <span class="detail-label">
                        Duración total
                    </span>

                    <strong class="detail-value">
                        {{ $totalDuration }} minutos
                    </strong>

                </div>

                <div class="detail-item">

                    <span class="detail-label">
                        Importe total
                    </span>

                    <strong class="detail-value">
                        ${{ number_format($totalPrice, 2) }}
                    </strong>

                </div>

            </div>

        </div>

    @else

        <div class="empty-state">

            <h3>
                Sin servicios registrados
            </h3>

            <p>
                No se encontraron servicios
                asociados a esta cita.
            </p>

        </div>

    @endif

</div>

{{-- ==========================================
     OBSERVACIONES DEL CLIENTE
========================================== --}}

<div class="card mb-4">

    <div class="card-header">

        <h3>
            Observaciones de la solicitud
        </h3>

    </div>

    <div class="card-body">

        @if($appointment->notes)

            <p style="white-space: pre-wrap;">{{ $appointment->notes }}</p>

        @else

            <p class="text-muted">
                No agregaste observaciones
                adicionales a esta solicitud.
            </p>

        @endif

    </div>

</div>

{{-- ==========================================
     INFORMACIÓN DE REVISIÓN
========================================== --}}

@if($appointment->reviewed_at)

    <div class="card mb-4">

        <div class="card-header">

            <h3>
                Revisión del encargado
            </h3>

        </div>

        <div class="card-body">

            <div class="detail-grid">

                <div class="detail-item">

                    <span class="detail-label">
                        Fecha de revisión
                    </span>

                    <strong class="detail-value">
                        {{ $appointment->reviewed_at->format('d/m/Y H:i') }}
                    </strong>

                </div>

                <div class="detail-item">

                    <span class="detail-label">
                        Resultado
                    </span>

                    <strong class="detail-value">
                        {{ $statusLabels[$appointment->status] ?? ucfirst($appointment->status) }}
                    </strong>

                </div>

            </div>

        </div>

    </div>

@endif

{{-- ==========================================
     MOTIVO DE RECHAZO
========================================== --}}

@if($appointment->status === 'rechazada')

    <div class="card mb-4">

        <div class="card-header">

            <h3>
                Motivo de rechazo
            </h3>

        </div>

        <div class="card-body">

            <div class="alert alert-danger">

                <p>
                    {{ $appointment->rejection_reason
                        ?: 'No se registró un motivo de rechazo.' }}
                </p>

            </div>

            <p class="text-muted mt-3">
                Puedes consultar otros horarios
                y realizar una nueva solicitud.
            </p>

            <a
                href="{{ route('cliente.citas.create') }}"
                class="btn btn-primary mt-3"
            >
                Solicitar otra cita
            </a>

        </div>

    </div>

@endif

{{-- ==========================================
     INFORMACIÓN DE ATENCIÓN
========================================== --}}

@if($appointment->status === 'atendida')

    <div class="card mb-4">

        <div class="card-header">

            <h3>
                Atención realizada
            </h3>

        </div>

        <div class="card-body">

            @if($appointment->attended_at)

                <div class="detail-item">

                    <span class="detail-label">
                        Fecha de atención
                    </span>

                    <strong class="detail-value">
                        {{ $appointment->attended_at->format('d/m/Y H:i') }}
                    </strong>

                </div>

            @endif

            <div class="mt-4">

                <span class="detail-label">
                    Observaciones del encargado
                </span>

                @if($appointment->attention_notes)

                    <p class="mt-3" style="white-space: pre-wrap;">{{ $appointment->attention_notes }}</p>

                @else

                    <p class="text-muted mt-3">
                        No se registraron observaciones
                        adicionales sobre la atención.
                    </p>

                @endif

            </div>

        </div>

    </div>

@endif

{{-- ==========================================
     INFORMACIÓN DE CANCELACIÓN
========================================== --}}

@if($appointment->status === 'cancelada')

    <div class="card mb-4">

        <div class="card-header">

            <h3>
                Información de cancelación
            </h3>

        </div>

        <div class="card-body">

            <p class="text-muted">
                Esta cita ya no está programada
                para recibir atención.
            </p>

            @if($appointment->cancelled_at)

                <p class="mt-3">

                    <strong>
                        Fecha de cancelación:
                    </strong>

                    {{ $appointment->cancelled_at->format('d/m/Y H:i') }}

                </p>

            @endif

        </div>

    </div>

@endif

{{-- ==========================================
     ACCIONES DISPONIBLES
========================================== --}}

<div class="card">

    <div class="card-header">

        <h3>
            Acciones disponibles
        </h3>

    </div>

    <div class="card-body">

        @if($canCancel)

            <p class="text-muted mb-4">
                Si ya no puedes asistir,
                puedes cancelar esta cita
                antes de su horario de inicio.
            </p>

            <form
                action="{{ route('cliente.citas.cancel', $appointment) }}"
                method="POST"
                data-confirm="¿Seguro que deseas cancelar esta cita? Esta acción cambiará su estado a cancelada."
            >

                @csrf
                @method('PATCH')

                <button
                    type="submit"
                    class="btn btn-danger"
                >
                    Cancelar cita
                </button>

            </form>

        @else

            <p class="text-muted">
                Esta cita no tiene disponible
                la opción de cancelación.
            </p>

        @endif

        <div class="form-actions mt-4">

            <a
                href="{{ route('cliente.citas.index') }}"
                class="btn btn-secondary"
            >
                Volver a mis citas
            </a>

            <a
                href="{{ route('cliente.citas.create') }}"
                class="btn btn-primary"
            >
                Solicitar otra cita
            </a>

        </div>

    </div>

</div>

@endsection
