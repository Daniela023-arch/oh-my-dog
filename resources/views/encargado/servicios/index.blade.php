
@extends('layouts.app')

@section('title', 'Administrar servicios | Oh My Dog!')

@section('content')
@php
    $serviceItems = $services->getCollection();
    $totalActivos = $serviceItems->where('is_active', true)->count();
    $totalInactivos = $serviceItems->where('is_active', false)->count();
@endphp

<div class="page-header">
    <div>
        <h1 class="page-title">Administrar servicios</h1>
        <p class="page-description">
            Consulta y administra los servicios de Oh My Dog!
        </p>
    </div>

    <div class="page-actions">
        <a href="{{ route('encargado.servicios.create') }}" class="btn btn-primary">
            Nuevo servicio
        </a>
    </div>
</div>

<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-top">
            <span class="stat-label">Total de servicios</span>
        </div>
        <div class="stat-value">{{ $services->total() }}</div>
    </div>

    <div class="stat-card">
        <div class="stat-top">
            <span class="stat-label">Activos en esta página</span>
        </div>
        <div class="stat-value">{{ $totalActivos }}</div>
    </div>

    <div class="stat-card">
        <div class="stat-top">
            <span class="stat-label">Inactivos en esta página</span>
        </div>
        <div class="stat-value">{{ $totalInactivos }}</div>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <h2>Listado de servicios</h2>
    </div>

    <div class="card-body">
        @if($services->count() > 0)
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Servicio</th>
                            <th>Descripción</th>
                            <th>Precio</th>
                            <th>Duración</th>
                            <th>Estado</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach($services as $service)
                            <tr>
                                <td>
                                    <strong>{{ $service->name }}</strong>
                                </td>

                                <td>
                                    {{ $service->description ?: 'Sin descripción' }}
                                </td>

                                <td>
                                    ${{ number_format($service->price, 2) }}
                                </td>

                                <td>
                                    {{ $service->duration_minutes }} minutos
                                </td>

                                <td>
                                    @if($service->is_active)
                                        <span class="badge badge-success">
                                            Activo
                                        </span>
                                    @else
                                        <span class="badge badge-danger">
                                            Inactivo
                                        </span>
                                    @endif
                                </td>

                                <td>
                                    <div class="table-actions">
                                        <a
                                            href="{{ route('encargado.servicios.show', $service->id) }}"
                                            class="btn btn-outline btn-sm"
                                        >
                                            Ver
                                        </a>

                                        <a
                                            href="{{ route('encargado.servicios.edit', $service->id) }}"
                                            class="btn btn-secondary btn-sm"
                                        >
                                            Editar
                                        </a>

                                        @if($service->is_active)
                                            <form
                                                action="{{ route('encargado.servicios.destroy', $service->id) }}"
                                                method="POST"
                                                onsubmit="return confirm('¿Desactivar este servicio?')"
                                            >
                                                @csrf
                                                @method('DELETE')

                                                <button type="submit" class="btn btn-danger btn-sm">
                                                    Desactivar
                                                </button>
                                            </form>
                                        @else
                                            <form
                                                action="{{ route('encargado.servicios.activate', $service->id) }}"
                                                method="POST"
                                            >
                                                @csrf
                                                @method('PATCH')

                                                <button type="submit" class="btn btn-primary btn-sm">
                                                    Activar
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

            <div class="pagination">
                {{ $services->links() }}
            </div>
        @else
            <div class="empty-state">
                <h3>No hay servicios registrados</h3>
                <p>Comienza agregando el primer servicio.</p>

                <a href="{{ route('encargado.servicios.create') }}" class="btn btn-primary">
                    Agregar servicio
                </a>
            </div>
        @endif
    </div>
</div>
@endsection
