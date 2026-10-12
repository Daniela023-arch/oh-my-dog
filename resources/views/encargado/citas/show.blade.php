
@extends('layouts.app')

@section('title', 'Detalle de cita | Oh My Dog!')

@section('content')

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
        'cancelada' => 'badge-secondary',
        'atendida' => 'badge-info',
    ];
@endphp

<div class="page-header">
    <div>
        <h1 class="page-title">
            Detalle de cita #{{ $appointment->id }}
        </h1>

        <p class="page-description">
            Consulta y administra la solicitud de servicio.
        </p>
    </div>

    <div class="page-actions">
        <a
            href="{{ route('encargado.citas.index') }}"
            class="btn btn-outline"
        >
            Volver a citas
        </a>
    </div>
</div>

@if(session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif

@if(session('error'))
    <div class="alert alert-danger">
        {{ session('error') }}
    </div>
@endif

@if($errors->any())
    <div class="alert alert-danger">
        <strong>No se pudo completar la operación.</strong>

        <ul>
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div class="card">

    <div class="card-header">
        <h2>Información de la cita</h2>

        <span class="badge {{ $statusClasses[$appointment->status] ?? 'badge-secondary' }}">
            {{ $statusLabels[$appointment->status] ?? ucfirst($appointment->status) }}
        </span>
    </div>

    <div class="card-body">

        <div class="detail-grid">

            <div class="detail-item">
                <span class="detail-label">Número de cita</span>
                <span class="detail-value">
                    #{{ $appointment->id }}
                </span>
            </div>

            <div class="detail-item">
                <span class="detail-label">Fecha</span>
                <span class="detail-value">
                    {{ $appointment->appointment_date->format('d/m/Y') }}
                </span>
            </div>

            <div class="detail-item">
                <span class="detail-label">Hora de inicio</span>
                <span class="detail-value">
                    {{ substr($appointment->start_time, 0, 5) }}
                </span>
            </div>

            <div class="detail-item">
                <span class="detail-label">Hora de finalización</span>
                <span class="detail-value">
                    {{ substr($appointment->end_time, 0, 5) }}
                </span>
            </div>

            <div class="detail-item">
                <span class="detail-label">Estado</span>
                <span class="detail-value">
                    {{ $statusLabels[$appointment->status] ?? ucfirst($appointment->status) }}
                </span>
            </div>

            <div class="detail-item">
                <span class="detail-label">Fecha de solicitud</span>
                <span class="detail-value">
                    {{ $appointment->created_at->format('d/m/Y H:i') }}
                </span>
            </div>

        </div>

        @if($appointment->notes)
            <div class="detail-item" style="margin-top: 24px;">
                <span class="detail-label">Notas del cliente</span>
                <p class="detail-value">
                    {{ $appointment->notes }}
                </p>
            </div>
        @endif

    </div>

</div>

<div class="dashboard-grid" style="margin-top: 24px;">

    <div class="card">
        <div class="card-header">
            <h2>Datos del cliente</h2>
        </div>

        <div class="card-body">
            <div class="detail-grid">

                <div class="detail-item">
                    <span class="detail-label">Nombre</span>
                    <span class="detail-value">
                        {{ $appointment->user->name }}
                    </span>
                </div>

                <div class="detail-item">
                    <span class="detail-label">Correo electrónico</span>
                    <span class="detail-value">
                        {{ $appointment->user->email }}
                    </span>
                </div>

                <div class="detail-item">
                    <span class="detail-label">Teléfono</span>
                    <span class="detail-value">
                        {{ $appointment->user->phone ?: 'No registrado' }}
                    </span>
                </div>

            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h2>Datos de la mascota</h2>
        </div>

        <div class="card-body">
            <div class="detail-grid">

                <div class="detail-item">
                    <span class="detail-label">Nombre</span>
                    <span class="detail-value">
                        {{ $appointment->pet->name }}
                    </span>
                </div>

                <div class="detail-item">
                    <span class="detail-label">Especie</span>
                    <span class="detail-value">
                        {{ $appointment->pet->species }}
                    </span>
                </div>

                <div class="detail-item">
                    <span class="detail-label">Raza</span>
                    <span class="detail-value">
                        {{ $appointment->pet->breed ?: 'No especificada' }}
                    </span>
                </div>

                <div class="detail-item">
                    <span class="detail-label">Sexo</span>
                    <span class="detail-value">
                        {{ $appointment->pet->sex
                            ? ucfirst($appointment->pet->sex)
                            : 'No especificado' }}
                    </span>
                </div>

            </div>
        </div>
    </div>

</div>

<div class="card" style="margin-top: 24px;">

    <div class="card-header">
        <h2>Servicios solicitados</h2>
    </div>

    <div class="card-body">

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
                    @foreach($appointment->services as $service)
                        <tr>
                            <td>{{ $service->name }}</td>

                            <td>
                                {{ $service->pivot->duration_minutes }}
                                minutos
                            </td>

                            <td>
                                ${{ number_format($service->pivot->price, 2) }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>

                <tfoot>
                    <tr>
                        <th>Total</th>

                        <th>
                            {{ $appointment->total_duration }}
                            minutos
                        </th>

                        <th>
                            ${{ number_format($appointment->total_price, 2) }}
                            MXN
                        </th>
                    </tr>
                </tfoot>

            </table>
        </div>

    </div>

</div>

@if($appointment->rejection_reason || $appointment->attention_notes)

    <div class="card" style="margin-top: 24px;">

        <div class="card-header">
            <h2>Observaciones de la gestión</h2>
        </div>

        <div class="card-body">

            @if($appointment->rejection_reason)
                <div class="detail-item">
                    <span class="detail-label">Motivo de rechazo</span>
                    <p class="detail-value">
                        {{ $appointment->rejection_reason }}
                    </p>
                </div>
            @endif

            @if($appointment->attention_notes)
                <div class="detail-item">
                    <span class="detail-label">Notas de atención</span>
                    <p class="detail-value">
                        {{ $appointment->attention_notes }}
                    </p>
                </div>
            @endif

        </div>
    </div>

@endif

@if($appointment->status === 'pendiente')

    <div class="card" style="margin-top: 24px;">

        <div class="card-header">
            <h2>Revisar solicitud</h2>
        </div>

        <div class="card-body">

            <p>
                Esta cita está pendiente de revisión.
                Puedes confirmarla si existe disponibilidad
                o rechazarla indicando el motivo.
            </p>

            <form
                action="{{ route('encargado.citas.confirm', $appointment->id) }}"
                method="POST"
                onsubmit="return confirm('¿Confirmar esta cita?')"
                style="margin-top: 20px;"
            >
                @csrf
                @method('PATCH')

                <button type="submit" class="btn btn-primary">
                    Confirmar cita
                </button>
            </form>

            <form
                action="{{ route('encargado.citas.reject', $appointment->id) }}"
                method="POST"
                style="margin-top: 24px;"
            >
                @csrf
                @method('PATCH')

                <div class="form-group">
                    <label for="rejection_reason" class="form-label">
                        Motivo del rechazo
                    </label>

                    <textarea
                        id="rejection_reason"
                        name="rejection_reason"
                        class="form-control form-textarea"
                        rows="4"
                        maxlength="1000"
                        required
                        placeholder="Explica por qué se rechaza la solicitud..."
                    >{{ old('rejection_reason') }}</textarea>
                </div>

                <button
                    type="submit"
                    class="btn btn-danger"
                    onclick="return confirm('¿Rechazar esta solicitud?')"
                >
                    Rechazar cita
                </button>
            </form>

        </div>
    </div>

@endif

@if($appointment->status === 'confirmada')

    <div class="card" style="margin-top: 24px;">

        <div class="card-header">
            <h2>Registrar atención</h2>
        </div>

        <div class="card-body">

            <p>
                Una vez realizado el servicio, registra la atención
                de esta cita.
            </p>

            <form
                action="{{ route('encargado.citas.attend', $appointment->id) }}"
                method="POST"
            >
                @csrf
                @method('PATCH')

                <div class="form-group" style="margin-top: 20px;">
                    <label for="attention_notes" class="form-label">
                        Notas de atención
                    </label>

                    <textarea
                        id="attention_notes"
                        name="attention_notes"
                        class="form-control form-textarea"
                        rows="4"
                        maxlength="1000"
                        placeholder="Describe cómo se realizó el servicio..."
                    >{{ old('attention_notes') }}</textarea>
                </div>

                <button
                    type="submit"
                    class="btn btn-primary"
                    onclick="return confirm('¿Marcar esta cita como atendida?')"
                >
                    Marcar como atendida
                </button>
            </form>

            <form
                action="{{ route('encargado.citas.cancel', $appointment->id) }}"
                method="POST"
                style="margin-top: 24px;"
            >
                @csrf
                @method('PATCH')

                <button
                    type="submit"
                    class="btn btn-danger"
                    onclick="return confirm('¿Cancelar esta cita confirmada?')"
                >
                    Cancelar cita
                </button>
            </form>

        </div>
    </div>

@endif

@endsection
