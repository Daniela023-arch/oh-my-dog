
@extends('layouts.app')

@section('title', 'Administrar horarios | Oh My Dog!')

@section('content')

<div class="page-header">
    <div>
        <h1 class="page-title">Horarios de atención</h1>
        <p class="page-description">
            Consulta y administra los horarios de atención de Oh My Dog!
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
        <h2>Horario semanal</h2>
    </div>

    <div class="card-body">

        <div class="table-responsive">
            <table class="table">

                <thead>
                    <tr>
                        <th>Día</th>
                        <th>Apertura</th>
                        <th>Cierre</th>
                        <th>Intervalo de citas</th>
                        <th>Estado</th>
                        <th>Acciones</th>
                    </tr>
                </thead>

                <tbody>

                    @forelse($schedules as $schedule)

                        <tr>
                            <td>
                                <strong>{{ $schedule->day_name }}</strong>
                            </td>

                            <td>
                                @if($schedule->is_active)
                                    {{ \Carbon\Carbon::parse($schedule->opening_time)->format('H:i') }}
                                @else
                                    —
                                @endif
                            </td>

                            <td>
                                @if($schedule->is_active)
                                    {{ \Carbon\Carbon::parse($schedule->closing_time)->format('H:i') }}
                                @else
                                    —
                                @endif
                            </td>

                            <td>
                                {{ $schedule->slot_minutes }} minutos
                            </td>

                            <td>
                                @if($schedule->is_active)
                                    <span class="badge badge-success">
                                        Abierto
                                    </span>
                                @else
                                    <span class="badge badge-danger">
                                        Cerrado
                                    </span>
                                @endif
                            </td>

                            <td>
                                <div class="table-actions">
                                    <a
                                        href="{{ route('encargado.horarios.edit', $schedule->id) }}"
                                        class="btn btn-secondary btn-sm"
                                    >
                                        Editar horario
                                    </a>
                                </div>
                            </td>
                        </tr>

                    @empty

                        <tr>
                            <td colspan="6">
                                <div class="empty-state">
                                    <h3>No hay horarios registrados</h3>
                                    <p>
                                        Todavía no se han configurado los horarios de atención.
                                    </p>
                                </div>
                            </td>
                        </tr>

                    @endforelse

                </tbody>

            </table>
        </div>

    </div>

</div>

<div class="card" style="margin-top: 24px;">

    <div class="card-header">
        <h2>Información importante</h2>
    </div>

    <div class="card-body">

        <p>
            Los horarios configurados determinan cuándo pueden
            solicitar citas los clientes.
        </p>

        <p>
            Cada día puede tener una hora de apertura,
            una hora de cierre y un intervalo de tiempo
            para organizar las citas.
        </p>

        <p>
            Si un día está desactivado, los clientes no podrán
            solicitar nuevas citas para ese día.
        </p>

        <p>
            El sistema también verifica las citas confirmadas
            antes de permitir cambios que puedan afectar
            las reservaciones existentes.
        </p>

    </div>

</div>

@endsection
