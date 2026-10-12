@extends('layouts.app')

@section('title', 'Nuevo servicio | Oh My Dog!')

@section('page-title', 'Registrar servicio')

@section('page-subtitle', 'Agrega un servicio al catálogo')

@section('heading', 'Nuevo servicio')

@section('heading-description', 'Registra un nuevo servicio de estética canina.')

@section('page-actions')

    <a href="{{ route('encargado.servicios.index') }}"
       class="btn btn-secondary">
        Volver a servicios
    </a>

@endsection

@section('content')

{{-- ==========================================
     INTRODUCCIÓN
========================================== --}}

<div class="card mb-4">

    <div class="card-body">

        <span class="eyebrow">
            ADMINISTRACIÓN DE SERVICIOS
        </span>

        <h2 class="mt-3">
            Registrar nuevo servicio
        </h2>

        <p class="text-muted mt-2">
            Completa los datos del servicio
            que deseas ofrecer a los clientes.

            El precio y la duración se utilizarán
            para calcular el costo total
            y los horarios de las citas.
        </p>

    </div>

</div>

{{-- ==========================================
     FORMULARIO
========================================== --}}

<div class="card">

    <div class="card-header">

        <div>

            <h3>
                Información del servicio
            </h3>

            <p class="text-muted">
                Los campos marcados con * son obligatorios.
            </p>

        </div>

    </div>

    <div class="card-body">

        <form
            action="{{ route('encargado.servicios.store') }}"
            method="POST"
            id="service-create-form"
        >

            @csrf

            {{-- ==================================
                 NOMBRE
            ================================== --}}

            <div class="form-group">

                <label for="name" class="form-label">
                    Nombre del servicio *
                </label>

                <input
                    type="text"
                    id="name"
                    name="name"
                    class="form-control @error('name') is-invalid @enderror"
                    value="{{ old('name') }}"
                    placeholder="Ejemplo: Baño medicado"
                    maxlength="150"
                    required
                    autofocus
                >

                @error('name')

                    <span class="form-error" role="alert">
                        {{ $message }}
                    </span>

                @enderror

                <p class="form-hint">
                    Utiliza un nombre claro para
                    identificar el servicio
                    dentro del catálogo.
                </p>

            </div>

            {{-- ==================================
                 DESCRIPCIÓN
            ================================== --}}

            <div class="form-group">

                <label for="description" class="form-label">
                    Descripción del servicio
                </label>

                <textarea
                    id="description"
                    name="description"
                    class="form-textarea @error('description') is-invalid @enderror"
                    rows="5"
                    maxlength="1000"
                    placeholder="Describe qué incluye el servicio y los cuidados que se realizan."
                >{{ old('description') }}</textarea>

                @error('description')

                    <span class="form-error" role="alert">
                        {{ $message }}
                    </span>

                @enderror

                <p class="form-hint">
                    La descripción ayudará
                    a los clientes a conocer
                    los cuidados incluidos.
                </p>

            </div>

            {{-- ==================================
                 PRECIO Y DURACIÓN
            ================================== --}}

            <div class="form-grid">

                <div class="form-group">

                    <label for="price" class="form-label">
                        Precio del servicio (MXN) *
                    </label>

                    <input
                        type="number"
                        id="price"
                        name="price"
                        class="form-control @error('price') is-invalid @enderror"
                        value="{{ old('price') }}"
                        placeholder="Ejemplo: 250.00"
                        min="0"
                        max="99999999.99"
                        step="0.01"
                        inputmode="decimal"
                        required
                    >

                    @error('price')

                        <span class="form-error" role="alert">
                            {{ $message }}
                        </span>

                    @enderror

                    <p class="form-hint">
                        Ingresa el precio en pesos mexicanos.
                    </p>

                </div>

                <div class="form-group">

                    <label for="duration_minutes" class="form-label">
                        Duración estimada (minutos) *
                    </label>

                    <input
                        type="number"
                        id="duration_minutes"
                        name="duration_minutes"
                        class="form-control @error('duration_minutes') is-invalid @enderror"
                        value="{{ old('duration_minutes') }}"
                        placeholder="Ejemplo: 60"
                        min="1"
                        max="1440"
                        step="1"
                        required
                    >

                    @error('duration_minutes')

                        <span class="form-error" role="alert">
                            {{ $message }}
                        </span>

                    @enderror

                    <p class="form-hint">
                        Esta duración se utilizará
                        para calcular los espacios
                        disponibles en la agenda.
                    </p>

                </div>

            </div>

            {{-- ==================================
                 INFORMACIÓN IMPORTANTE
            ================================== --}}

            <div class="alert alert-info">

                <p>
                    El nuevo servicio se registrará
                    como activo y estará disponible
                    para nuevas solicitudes de citas.

                    Posteriormente podrás editar
                    su precio, duración y descripción
                    desde el panel del encargado.
                </p>

            </div>

            {{-- ==================================
                 BOTONES
            ================================== --}}

            <div class="form-actions">

                <a
                    href="{{ route('encargado.servicios.index') }}"
                    class="btn btn-secondary"
                >
                    Cancelar
                </a>

                <button
                    type="submit"
                    class="btn btn-primary"
                    id="save-service-button"
                >
                    Guardar servicio
                </button>

            </div>

        </form>

    </div>

</div>

{{-- ==========================================
     RECOMENDACIONES
========================================== --}}

<div class="card mt-4">

    <div class="card-header">

        <h3>
            Recomendaciones para registrar servicios
        </h3>

    </div>

    <div class="card-body">

        <div class="detail-grid">

            <div class="detail-item">

                <span class="detail-label">
                    Nombre
                </span>

                <p class="text-muted mt-2">
                    Utiliza nombres fáciles
                    de reconocer para los clientes.
                </p>

            </div>

            <div class="detail-item">

                <span class="detail-label">
                    Precio
                </span>

                <p class="text-muted mt-2">
                    Registra el importe que
                    se utilizará al solicitar
                    el servicio.
                </p>

            </div>

            <div class="detail-item">

                <span class="detail-label">
                    Duración
                </span>

                <p class="text-muted mt-2">
                    Considera el tiempo necesario
                    para realizar correctamente
                    el servicio.
                </p>

            </div>

            <div class="detail-item">

                <span class="detail-label">
                    Descripción
                </span>

                <p class="text-muted mt-2">
                    Explica qué incluye
                    el servicio y sus principales
                    características.
                </p>

            </div>

        </div>

    </div>

</div>

@endsection

@push('scripts')

<script>
    document.addEventListener('DOMContentLoaded', function () {

        const form = document.getElementById(
            'service-create-form'
        );

        const saveButton = document.getElementById(
            'save-service-button'
        );

        if (!form || !saveButton) {
            return;
        }

        form.addEventListener('submit', function () {

            if (!form.checkValidity()) {
                return;
            }

            saveButton.disabled = true;

            saveButton.textContent = 'Guardando servicio...';

        });

    });
</script>

@endpush
