
@extends('layouts.app')

@section('title', $pet->name . ' | Oh My Dog!')

@section('page-title', 'Detalles de mascota')

@section('page-subtitle', 'Información y seguimiento de tu mascota')

@section('heading', $pet->name)

@section('heading-description', 'Consulta los datos registrados y el historial de citas de tu mascota.')

@section('page-actions')

    <a href="{{ route('cliente.mascotas.index') }}"
       class="btn btn-secondary">
        Volver a mis mascotas
    </a>

    <a href="{{ route('cliente.mascotas.edit', $pet) }}"
       class="btn btn-primary">
        Editar mascota
    </a>

@endsection

@section('content')

{{-- ==========================================
     PRESENTACIÓN DE LA MASCOTA
========================================== --}}

<div class="card mb-4">

    <div class="card-body">

        <div class="row items-center gap-4">

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

            <div>

                <span class="eyebrow">
                    PERFIL DE MASCOTA
                </span>

                <h2 class="mt-2">
                    {{ $pet->name }}
                </h2>

                <p class="text-muted">
                    {{ $pet->species }}

                    @if($pet->breed)
                        · {{ $pet->breed }}
                    @endif
                </p>

                <div class="mt-3">

                    @if($pet->is_active)

                        <span class="badge badge-success">
                            Mascota activa
                        </span>

                    @else

                        <span class="badge badge-neutral">
                            Mascota inactiva
                        </span>

                    @endif

                </div>

            </div>

        </div>

    </div>

</div>

{{-- ==========================================
     DATOS DE LA MASCOTA
========================================== --}}

<div class="card">

    <div class="card-header">

        <h3>
            Información general
        </h3>

    </div>

    <div class="card-body">

        <div class="detail-grid">

            {{-- NOMBRE --}}

            <div class="detail-item">

                <span class="detail-label">
                    Nombre
                </span>

                <strong class="detail-value">
                    {{ $pet->name }}
                </strong>

            </div>

            {{-- ESPECIE --}}

            <div class="detail-item">

                <span class="detail-label">
                    Especie
                </span>

                <strong class="detail-value">
                    {{ $pet->species }}
                </strong>

            </div>

            {{-- RAZA --}}

            <div class="detail-item">

                <span class="detail-label">
                    Raza
                </span>

                <strong class="detail-value">
                    {{ $pet->breed ?: 'No registrada' }}
                </strong>

            </div>

            {{-- SEXO --}}

            <div class="detail-item">

                <span class="detail-label">
                    Sexo
                </span>

                <strong class="detail-value">

                    @if($pet->sex === 'macho')
                        Macho
                    @elseif($pet->sex === 'hembra')
                        Hembra
                    @else
                        No especificado
                    @endif

                </strong>

            </div>

            {{-- NACIMIENTO --}}

            <div class="detail-item">

                <span class="detail-label">
                    Fecha de nacimiento
                </span>

                <strong class="detail-value">

                    {{ $pet->birth_date
                        ? $pet->birth_date->format('d/m/Y')
                        : 'No registrada' }}

                </strong>

            </div>

            {{-- EDAD --}}

            <div class="detail-item">

                <span class="detail-label">
                    Edad
                </span>

                <strong class="detail-value">
                    {{ $pet->age ?? 'No registrada' }}
                </strong>

            </div>

            {{-- PESO --}}

            <div class="detail-item">

                <span class="detail-label">
                    Peso
                </span>

                <strong class="detail-value">

                    @if($pet->weight !== null)

                        {{ number_format((float) $pet->weight, 2) }} kg

                    @else

                        No registrado

                    @endif

                </strong>

            </div>

            {{-- ESTADO --}}

            <div class="detail-item">

                <span class="detail-label">
                    Estado
                </span>

                <strong class="detail-value">

                    {{ $pet->is_active ? 'Activa' : 'Inactiva' }}

                </strong>

            </div>

            {{-- REGISTRO --}}

            <div class="detail-item">

                <span class="detail-label">
                    Fecha de registro
                </span>

                <strong class="detail-value">
                    {{ $pet->created_at->format('d/m/Y') }}
                </strong>

            </div>

        </div>

    </div>

</div>

{{-- ==========================================
     OBSERVACIONES
========================================== --}}

<div class="card mt-4">

    <div class="card-header">

        <h3>
            Observaciones y cuidados
        </h3>

    </div>

    <div class="card-body">

        @if($pet->notes)

            <p style="white-space: pre-wrap;">{{ $pet->notes }}</p>

        @else

            <p class="text-muted">
                No se han registrado observaciones
                adicionales para esta mascota.
            </p>

        @endif

    </div>

</div>

{{-- ==========================================
     HISTORIAL DE CITAS
========================================== --}}

<div class="card mt-4">

    <div class="card-header">

        <div>

            <h3>
                Historial de citas
            </h3>

            <p class="text-muted">
                Citas relacionadas con {{ $pet->name }}.
            </p>

        </div>

        <a href="{{ route('cliente.citas.index') }}"
           class="btn btn-secondary btn-sm">
            Mis citas
        </a>

    </div>

    @php
        $petAppointments = $pet->appointments()
            ->orderByDesc('appointment_date')
            ->orderByDesc('start_time')
            ->get();

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

    @if($petAppointments->isNotEmpty())

        <div class="table-responsive">

            <table class="table">

                <thead>

                    <tr>
                        <th>Fecha</th>
                        <th>Horario</th>
                        <th>Estado</th>
                        <th>Acción</th>
                    </tr>

                </thead>

                <tbody>

                    @foreach($petAppointments as $appointment)

                        <tr>

                            <td>
                                {{ $appointment->appointment_date->format('d/m/Y') }}
                            </td>

                            <td>
                                {{ substr($appointment->start_time, 0, 5) }}
                                -
                                {{ substr($appointment->end_time, 0, 5) }}
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
                Sin citas registradas
            </h3>

            <p>
                Todavía no hay citas asociadas
                a esta mascota.
            </p>

        </div>

    @endif

</div>

{{-- ==========================================
     ACCIONES
========================================== --}}

<div class="dashboard-grid mt-4">

    {{-- GESTIÓN DE MASCOTA --}}

    <div class="card">

        <div class="card-header">
            <h3>
                Administrar mascota
            </h3>
        </div>

        <div class="card-body">

            <p class="text-muted mb-4">
                Puedes actualizar la información
                o cambiar el estado de esta mascota.
            </p>

            <div class="form-actions">

                <a href="{{ route('cliente.mascotas.edit', $pet) }}"
                   class="btn btn-secondary">
                    Editar información
                </a>

                @if($pet->is_active)

                    <form
                        action="{{ route('cliente.mascotas.destroy', $pet) }}"
                        method="POST"
                        data-confirm="¿Deseas desactivar esta mascota? No podrás hacerlo si tiene citas futuras pendientes o confirmadas."
                    >

                        @csrf
                        @method('DELETE')

                        <button type="submit"
                                class="btn btn-danger">
                            Desactivar mascota
                        </button>

                    </form>

                @else

                    <form
                        action="{{ route('cliente.mascotas.activate', $pet) }}"
                        method="POST"
                    >

                        @csrf
                        @method('PATCH')

                        <button type="submit"
                                class="btn btn-primary">
                            Reactivar mascota
                        </button>

                    </form>

                @endif

            </div>

        </div>

    </div>

    {{-- SOLICITAR CITA --}}

    <div class="card">

        <div class="card-header">
            <h3>
                Servicios de estética
            </h3>
        </div>

        <div class="card-body">

            <p class="text-muted mb-4">
                Solicita una cita para que
                {{ $pet->name }} reciba los cuidados
                que necesita.
            </p>

            @if($pet->is_active)

                <a
                    href="{{ route('cliente.citas.create', ['pet' => $pet->id]) }}"
                    class="btn btn-primary btn-block"
                >
                    Solicitar cita para {{ $pet->name }}
                </a>

            @else

                <div class="alert alert-info">
                    Reactiva esta mascota para
                    poder solicitar nuevas citas.
                </div>

            @endif

            <a href="{{ route('servicios') }}"
               class="btn btn-outline btn-block mt-3">
                Consultar servicios
            </a>

        </div>

    </div>

</div>

@endsection
