
@extends('layouts.app')

@section('title', 'Administrar citas | Oh My Dog!')

@section('content')

<div class="page-header">
    <div>
        <h1 class="page-title">Administrar citas</h1>
        <p class="page-description">
            Consulta las solicitudes y administra las citas de los clientes.
        </p>
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

<div class="card">
    <div class="card-header">
        <h2>Listado de citas</h2>
    </div>

    <div class="card-body">

        <form
            action="{{ route('encargado.citas.index') }}"
            method="GET"
            class="filters-bar"
        >
            <div class="form-group">
                <label for="status" class="form-label">Estado</label>

                <select
                    name="status"
                    id="status"
                    class="form-control form-select"
                >
                    <option value="">Todos los estados</option>

                    @foreach([
                        'pendiente' => 'Pendiente',
                        'confirmada' => 'Confirmada',
                        'rechazada' => 'Rechazada',
                        'cancelada' => 'Cancelada',
                        'atendida' => 'Atendida'
                    ] as $value => $label)

                        <option
                            value="{{ $value }}"
                            {{ request('status') === $value ? 'selected' : '' }}
                        >
                            {{ $label }}
                        </option>

                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label for="date" class="form-label">Fecha</label>

                <input
                    type="date"
                    id="date"
                    name="date"
                    class="form-control"
                    value="{{ request('date') }}"
                >
            </div>

            <div class="form-actions">
                <button type="submit" class="btn btn-primary">
                    Filtrar
                </button>

                <a
                    href="{{ route('encargado.citas.index') }}"
                    class="btn btn-outline"
                >
                    Limpiar
                </a>
            </div>
        </form>

        @if($appointments->count() > 0)

            <div class="table-responsive">
                <table class="table">

                    <thead>
                        <tr>
                            <th>Fecha</th>
                            <th>Hora</th>
                            <th>Cliente</th>
                            <th>Mascota</th>
                            <th>Servicios</th>
                            <th>Estado</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach($appointments as $appointment)

                            @php
                                $statusLabels = [
                                    'pendiente' => 'Pendiente',
                                    'confirmada' => 'Confirmada',
                                    'rechazada' => 'Rechazada',
                                    'cancelada' => 'Cancelada',
                                    'atendida' => 'Atendida'
                                ];

                                $statusClasses = [
                                    'pendiente' => 'badge-warning',
                                    'confirmada' => 'badge-success',
                                    'rechazada' => 'badge-danger',
                                    'cancelada' => 'badge-secondary',
                                    'atendida' => 'badge-info'
                                ];
                            @endphp

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
                                    <strong>
                                        {{ $appointment->user->name }}
                                    </strong>

                                    <div class="table-secondary">
                                        {{ $appointment->user->email }}
                                    </div>
                                </td>

                                <td>
                                    {{ $appointment->pet->name }}
                                </td>

                                <td>
                                    @foreach($appointment->services as $service)
                                        <div>{{ $service->name }}</div>
                                    @endforeach
                                </td>

                                <td>
                                    <span class="badge {{ $statusClasses[$appointment->status] ?? 'badge-secondary' }}">
                                        {{ $statusLabels[$appointment->status] ?? ucfirst($appointment->status) }}
                                    </span>
                                </td>

                                <td>
                                    <div class="table-actions">
                                        <a
                                            href="{{ route('encargado.citas.show', $appointment->id) }}"
                                            class="btn btn-outline btn-sm"
                                        >
                                            Ver detalles
                                        </a>
                                    </div>
                                </td>
                            </tr>

                        @endforeach
                    </tbody>

                </table>
            </div>

            @if(method_exists($appointments, 'links'))
                <div class="pagination">
                    {{ $appointments->links() }}
                </div>
            @endif

        @else

            <div class="empty-state">
                <h3>No se encontraron citas</h3>
                <p>
                    Todavía no hay citas registradas o ninguna coincide
                    con los filtros seleccionados.
                </p>
            </div>

        @endif

    </div>
</div>

@endsection
