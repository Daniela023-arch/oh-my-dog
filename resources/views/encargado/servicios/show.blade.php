
@extends('layouts.app')

@section('title', 'Detalle del servicio | Oh My Dog!')

@section('content')

<div class="page-header">
    <div>
        <h1 class="page-title">Detalle del servicio</h1>
        <p class="page-description">
            Información del servicio seleccionado.
        </p>
    </div>

    <div class="page-actions">
        <a href="{{ route('encargado.servicios.index') }}"
           class="btn btn-outline">
            Volver a servicios
        </a>

        <a href="{{ route('encargado.servicios.edit', $service->id) }}"
           class="btn btn-primary">
            Editar servicio
        </a>
    </div>
</div>

<div class="card">

    <div class="card-header">
        <h2>{{ $service->name }}</h2>

        @if($service->is_active)
            <span class="badge badge-success">Activo</span>
        @else
            <span class="badge badge-danger">Inactivo</span>
        @endif
    </div>

    <div class="card-body">

        <div class="detail-grid">

            <div class="detail-item">
                <span class="detail-label">Nombre</span>
                <span class="detail-value">
                    {{ $service->name }}
                </span>
            </div>

            <div class="detail-item">
                <span class="detail-label">Precio</span>
                <span class="detail-value">
                    ${{ number_format($service->price, 2) }} MXN
                </span>
            </div>

            <div class="detail-item">
                <span class="detail-label">Duración estimada</span>
                <span class="detail-value">
                    {{ $service->duration_minutes }} minutos
                </span>
            </div>

            <div class="detail-item">
                <span class="detail-label">Estado</span>
                <span class="detail-value">
                    {{ $service->is_active ? 'Activo' : 'Inactivo' }}
                </span>
            </div>

            <div class="detail-item">
                <span class="detail-label">Fecha de registro</span>
                <span class="detail-value">
                    {{ $service->created_at->format('d/m/Y') }}
                </span>
            </div>

            <div class="detail-item">
                <span class="detail-label">Última actualización</span>
                <span class="detail-value">
                    {{ $service->updated_at->format('d/m/Y H:i') }}
                </span>
            </div>

        </div>

        <div class="detail-item" style="margin-top: 24px;">
            <span class="detail-label">Descripción</span>

            <p class="detail-value">
                {{ $service->description ?: 'Este servicio no tiene descripción.' }}
            </p>
        </div>

    </div>

    <div class="card-footer">

        @if($service->is_active)

            <form action="{{ route('encargado.servicios.destroy', $service->id) }}"
                  method="POST"
                  onsubmit="return confirm('¿Seguro que deseas desactivar este servicio?')">

                @csrf
                @method('DELETE')

                <button type="submit" class="btn btn-danger">
                    Desactivar servicio
                </button>
            </form>

        @else

            <form action="{{ route('encargado.servicios.activate', $service->id) }}"
                  method="POST">

                @csrf
                @method('PATCH')

                <button type="submit" class="btn btn-primary">
                    Activar servicio
                </button>
            </form>

        @endif

    </div>

</div>

@endsection
