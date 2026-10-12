
@extends('layouts.app')

@section('title', 'Editar servicio | Oh My Dog!')

@section('content')

<div class="page-header">
    <div>
        <h1 class="page-title">Editar servicio</h1>
        <p class="page-description">
            Modifica la información del servicio seleccionado.
        </p>
    </div>

    <div class="page-actions">
        <a href="{{ route('encargado.servicios.index') }}"
           class="btn btn-outline">
            Volver a servicios
        </a>
    </div>
</div>

@if($errors->any())
    <div class="alert alert-danger">
        <strong>No se pudieron guardar los cambios.</strong>
        <ul>
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div class="card">

    <div class="card-header">
        <h2>Información del servicio</h2>
    </div>

    <div class="card-body">

        <form action="{{ route('encargado.servicios.update', $service->id) }}"
              method="POST">

            @csrf
            @method('PUT')

            <div class="form-grid">

                <div class="form-group">
                    <label for="name" class="form-label">
                        Nombre del servicio
                    </label>

                    <input
                        type="text"
                        id="name"
                        name="name"
                        class="form-control @error('name') is-invalid @enderror"
                        value="{{ old('name', $service->name) }}"
                        maxlength="120"
                        required
                    >

                    @error('name')
                        <span class="form-error">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="price" class="form-label">
                        Precio (MXN)
                    </label>

                    <input
                        type="number"
                        id="price"
                        name="price"
                        class="form-control @error('price') is-invalid @enderror"
                        value="{{ old('price', $service->price) }}"
                        min="0"
                        max="99999999.99"
                        step="0.01"
                        required
                    >

                    @error('price')
                        <span class="form-error">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="duration_minutes" class="form-label">
                        Duración (minutos)
                    </label>

                    <input
                        type="number"
                        id="duration_minutes"
                        name="duration_minutes"
                        class="form-control @error('duration_minutes') is-invalid @enderror"
                        value="{{ old('duration_minutes', $service->duration_minutes) }}"
                        min="1"
                        max="1440"
                        step="1"
                        required
                    >

                    @error('duration_minutes')
                        <span class="form-error">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label class="form-label">
                        Estado actual
                    </label>

                    <div>
                        @if($service->is_active)
                            <span class="badge badge-success">
                                Activo
                            </span>
                        @else
                            <span class="badge badge-danger">
                                Inactivo
                            </span>
                        @endif
                    </div>

                    <p class="form-hint">
                        Puedes activar o desactivar el servicio desde su página de detalles.
                    </p>
                </div>

            </div>

            <div class="form-group">
                <label for="description" class="form-label">
                    Descripción
                </label>

                <textarea
                    id="description"
                    name="description"
                    rows="5"
                    maxlength="1000"
                    class="form-control form-textarea @error('description') is-invalid @enderror"
                    placeholder="Describe el servicio..."
                >{{ old('description', $service->description) }}</textarea>

                @error('description')
                    <span class="form-error">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-actions">

                <a href="{{ route('encargado.servicios.show', $service->id) }}"
                   class="btn btn-outline">
                    Cancelar
                </a>

                <button type="submit" class="btn btn-primary">
                    Guardar cambios
                </button>

            </div>

        </form>

    </div>

</div>

@endsection
