
@extends('layouts.app')

@section('title', 'Registrar mascota | Oh My Dog!')

@section('page-title', 'Registrar mascota')

@section('page-subtitle', 'Agrega una nueva mascota a tu cuenta')

@section('heading', 'Nueva mascota')

@section('heading-description', 'Completa la información de tu mascota para comenzar a gestionar sus citas.')

@section('page-actions')

    <a href="{{ route('cliente.mascotas.index') }}"
       class="btn btn-secondary">

        Volver a mis mascotas

    </a>

@endsection

@section('content')

{{-- ==========================================
     INFORMACIÓN INICIAL
========================================== --}}

<div class="card mb-4">

    <div class="card-body">

        <span class="eyebrow">
            REGISTRO DE MASCOTAS
        </span>

        <h2 class="mt-3">
            Conozcamos a tu mejor amigo
        </h2>

        <p class="text-muted mt-2">

            Ingresa los datos de tu mascota.
            Esta información nos ayudará
            a identificarla y organizar
            sus citas de estética canina.

        </p>

    </div>

</div>

{{-- ==========================================
     FORMULARIO DE REGISTRO
========================================== --}}

<div class="card">

    <div class="card-header">

        <div>

            <h3>
                Datos de la mascota
            </h3>

            <p class="text-muted">
                Los campos marcados con * son obligatorios.
            </p>

        </div>

    </div>

    <div class="card-body">

        <form
            action="{{ route('cliente.mascotas.store') }}"
            method="POST"
            id="pet-create-form"
        >

            @csrf

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
                    value="{{ old('name') }}"
                    placeholder="Ejemplo: Max"
                    maxlength="100"
                    autocomplete="off"
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

                        <option value="">
                            Selecciona una especie
                        </option>

                        <option
                            value="Perro"
                            @selected(old('species', 'Perro') === 'Perro')
                        >
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
                        value="{{ old('breed') }}"
                        placeholder="Ejemplo: Golden Retriever"
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

                        <option value="">
                            No especificado
                        </option>

                        <option
                            value="macho"
                            @selected(old('sex') === 'macho')
                        >
                            Macho
                        </option>

                        <option
                            value="hembra"
                            @selected(old('sex') === 'hembra')
                        >
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
                        value="{{ old('birth_date') }}"
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
                    value="{{ old('weight') }}"
                    placeholder="Ejemplo: 12.50"
                    min="0.01"
                    max="9999.99"
                    step="0.01"
                    inputmode="decimal"
                >

                <p class="form-hint">
                    Ingresa el peso en kilogramos.
                    Puedes dejar este campo vacío
                    si no conoces el peso actual.
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
                    Observaciones adicionales
                </label>

                <textarea
                    id="notes"
                    name="notes"
                    class="form-textarea @error('notes') is-invalid @enderror"
                    rows="5"
                    maxlength="1000"
                    placeholder="Ejemplo: Es sensible al ruido, necesita cuidados especiales o se pone nervioso durante el baño."
                >{{ old('notes') }}</textarea>

                <p class="form-hint">
                    Puedes indicar información
                    importante para el cuidado
                    de tu mascota.
                </p>

                @error('notes')

                    <span class="form-error" role="alert">
                        {{ $message }}
                    </span>

                @enderror

            </div>

            {{-- ==================================
                 AVISO
            ================================== --}}

            <div class="alert alert-info">

                <p>
                    La mascota quedará asociada
                    a tu cuenta y se registrará
                    como activa.

                    Después podrás seleccionarla
                    al solicitar una cita.
                </p>

            </div>

            {{-- ==================================
                 BOTONES
            ================================== --}}

            <div class="form-actions">

                <a
                    href="{{ route('cliente.mascotas.index') }}"
                    class="btn btn-secondary"
                >
                    Cancelar
                </a>

                <button
                    type="submit"
                    class="btn btn-primary"
                    id="save-pet-button"
                >
                    Guardar mascota
                </button>

            </div>

        </form>

    </div>

</div>

@endsection

@push('scripts')

<script>
    document.addEventListener('DOMContentLoaded', function () {

        const form = document.getElementById(
            'pet-create-form'
        );

        const saveButton = document.getElementById(
            'save-pet-button'
        );

        if (!form || !saveButton) {
            return;
        }

        // Evitar que se envíe dos veces el formulario.

        form.addEventListener('submit', function () {

            if (!form.checkValidity()) {
                return;
            }

            saveButton.disabled = true;

            saveButton.textContent = 'Guardando mascota...';

        });

    });
</script>

@endpush
