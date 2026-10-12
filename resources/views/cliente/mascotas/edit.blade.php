
@extends('layouts.app')

@section('title', 'Editar ' . $pet->name . ' | Oh My Dog!')

@section('page-title', 'Editar mascota')

@section('page-subtitle', 'Actualiza la información de tu mascota')

@section('heading', 'Editar a ' . $pet->name)

@section('heading-description', 'Modifica los datos de tu mascota y guarda los cambios.')

@section('page-actions')

    <a href="{{ route('cliente.mascotas.show', $pet) }}"
       class="btn btn-secondary">
        Volver a detalles
    </a>

@endsection

@section('content')

{{-- ==========================================
     INFORMACIÓN INICIAL
========================================== --}}

<div class="card mb-4">

    <div class="card-body">

        <span class="eyebrow">
            ACTUALIZACIÓN DE MASCOTA
        </span>

        <h2 class="mt-3">
            Información de {{ $pet->name }}
        </h2>

        <p class="text-muted mt-2">
            Mantén actualizados los datos de tu
            mascota para facilitar la atención
            durante sus citas de estética canina.
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

{{-- ==========================================
     FORMULARIO DE EDICIÓN
========================================== --}}

<div class="card">

    <div class="card-header">

        <div>

            <h3>
                Editar datos de la mascota
            </h3>

            <p class="text-muted">
                Los campos marcados con * son obligatorios.
            </p>

        </div>

    </div>

    <div class="card-body">

        <form
            action="{{ route('cliente.mascotas.update', $pet) }}"
            method="POST"
            id="pet-edit-form"
        >

            @csrf
            @method('PUT')

            {{-- ==================================
                 NOMBRE
            ================================== --}}

            <div class="form-group">

                <label for="name" class="form-label">
                    Nombre de la mascota *
                </label>

                <input
                    type="text"
                    id="name"
                    name="name"
                    class="form-control @error('name') is-invalid @enderror"
                    value="{{ old('name', $pet->name) }}"
                    placeholder="Nombre de la mascota"
                    maxlength="100"
                    required
                    autofocus
                >

                @error('name')
                    <span class="form-error" role="alert">
                        {{ $message }}
                    </span>
                @enderror

            </div>

            {{-- ==================================
                 ESPECIE Y RAZA
            ================================== --}}

            <div class="form-grid">

                <div class="form-group">

                    <label for="species" class="form-label">
                        Especie *
                    </label>

                    <select
                        id="species"
                        name="species"
                        class="form-select @error('species') is-invalid @enderror"
                        required
                    >

                        <option value="Perro"
                            @selected(old('species', $pet->species) === 'Perro')>
                            Perro
                        </option>

                    </select>

                    @error('species')
                        <span class="form-error" role="alert">
                            {{ $message }}
                        </span>
                    @enderror

                </div>

                <div class="form-group">

                    <label for="breed" class="form-label">
                        Raza
                    </label>

                    <input
                        type="text"
                        id="breed"
                        name="breed"
                        class="form-control @error('breed') is-invalid @enderror"
                        value="{{ old('breed', $pet->breed) }}"
                        placeholder="Ejemplo: Schnauzer"
                        maxlength="100"
                    >

                    @error('breed')
                        <span class="form-error" role="alert">
                            {{ $message }}
                        </span>
                    @enderror

                </div>

            </div>

            {{-- ==================================
                 SEXO Y FECHA DE NACIMIENTO
            ================================== --}}

            <div class="form-grid">

                <div class="form-group">

                    <label for="sex" class="form-label">
                        Sexo
                    </label>

                    <select
                        id="sex"
                        name="sex"
                        class="form-select @error('sex') is-invalid @enderror"
                    >

                        <option value=""
                            @selected(old('sex', $pet->sex) === null || old('sex', $pet->sex) === '')>
                            No especificado
                        </option>

                        <option value="macho"
                            @selected(old('sex', $pet->sex) === 'macho')>
                            Macho
                        </option>

                        <option value="hembra"
                            @selected(old('sex', $pet->sex) === 'hembra')>
                            Hembra
                        </option>

                    </select>

                    @error('sex')
                        <span class="form-error" role="alert">
                            {{ $message }}
                        </span>
                    @enderror

                </div>

                <div class="form-group">

                    <label for="birth_date" class="form-label">
                        Fecha de nacimiento
                    </label>

                    <input
                        type="date"
                        id="birth_date"
                        name="birth_date"
                        class="form-control @error('birth_date') is-invalid @enderror"
                        value="{{ old('birth_date', $pet->birth_date?->format('Y-m-d')) }}"
                        max="{{ now()->toDateString() }}"
                    >

                    @error('birth_date')
                        <span class="form-error" role="alert">
                            {{ $message }}
                        </span>
                    @enderror

                </div>

            </div>

            {{-- ==================================
                 PESO
            ================================== --}}

            <div class="form-group">

                <label for="weight" class="form-label">
                    Peso aproximado (kg)
                </label>

                <input
                    type="number"
                    id="weight"
                    name="weight"
                    class="form-control @error('weight') is-invalid @enderror"
                    value="{{ old('weight', $pet->weight) }}"
                    placeholder="Ejemplo: 12.50"
                    min="0.01"
                    max="9999.99"
                    step="0.01"
                    inputmode="decimal"
                >

                <p class="form-hint">
                    Puedes actualizar el peso
                    cuando tengas una medición más reciente.
                </p>

                @error('weight')
                    <span class="form-error" role="alert">
                        {{ $message }}
                    </span>
                @enderror

            </div>

            {{-- ==================================
                 OBSERVACIONES
            ================================== --}}

            <div class="form-group">

                <label for="notes" class="form-label">
                    Observaciones y cuidados especiales
                </label>

                <textarea
                    id="notes"
                    name="notes"
                    class="form-textarea @error('notes') is-invalid @enderror"
                    rows="5"
                    maxlength="1000"
                    placeholder="Agrega información importante sobre tu mascota."
                >{{ old('notes', $pet->notes) }}</textarea>

                <p class="form-hint">
                    Puedes registrar información sobre
                    su comportamiento, sensibilidad
                    o necesidades de cuidado.
                </p>

                @error('notes')
                    <span class="form-error" role="alert">
                        {{ $message }}
                    </span>
                @enderror

            </div>

            {{-- ==================================
                 INFORMACIÓN DEL REGISTRO
            ================================== --}}

            <div class="alert alert-info">

                <p>
                    Al guardar los cambios,
                    se actualizará la información
                    de esta mascota sin eliminar
                    su historial de citas.
                </p>

            </div>

            {{-- ==================================
                 BOTONES
            ================================== --}}

            <div class="form-actions">

                <a
                    href="{{ route('cliente.mascotas.show', $pet) }}"
                    class="btn btn-secondary"
                >
                    Cancelar
                </a>

                <button
                    type="submit"
                    class="btn btn-primary"
                    id="save-pet-button"
                >
                    Guardar cambios
                </button>

            </div>

        </form>

    </div>

</div>

{{-- ==========================================
     ESTADO DE LA MASCOTA
========================================== --}}

<div class="card mt-4">

    <div class="card-header">

        <h3>
            Estado de la mascota
        </h3>

    </div>

    <div class="card-body">

        @if($pet->is_active)

            <p class="text-muted mb-4">
                Esta mascota está activa y puede
                utilizarse para solicitar nuevas citas.
            </p>

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

            <p class="text-muted mb-4">
                Esta mascota está inactiva.
                Puedes reactivarla para solicitar
                nuevas citas.
            </p>

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

@endsection

@push('scripts')

<script>
    document.addEventListener('DOMContentLoaded', function () {

        const form = document.getElementById('pet-edit-form');

        const saveButton = document.getElementById(
            'save-pet-button'
        );

        if (!form || !saveButton) {
            return;
        }

        form.addEventListener('submit', function () {

            if (!form.checkValidity()) {
                return;
            }

            saveButton.disabled = true;
            saveButton.textContent = 'Guardando cambios...';

        });

    });
</script>

@endpush
