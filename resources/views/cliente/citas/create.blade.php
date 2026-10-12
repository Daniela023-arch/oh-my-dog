
@extends('layouts.app')

@section('title', 'Solicitar cita | Oh My Dog!')

@section('page-title', 'Solicitar cita')

@section('page-subtitle', 'Agenda un servicio para tu mascota')

@section('heading', 'Nueva solicitud de cita')

@section('heading-description', 'Selecciona tu mascota, los servicios y un horario disponible.')

@section('page-actions')

    <a href="{{ route('cliente.citas.index') }}"
       class="btn btn-secondary">
        Volver a mis citas
    </a>

@endsection

@section('content')

@php
    /*
     * El controlador proporciona las mascotas
     * y los servicios disponibles.
     */

    $selectedPet = old('pet_id', request('pet'));

    $selectedServices = array_map(
        'strval',
        old('services', [])
    );
@endphp

{{-- ==========================================
     INTRODUCCIÓN
========================================== --}}

<div class="card mb-4">

    <div class="card-body">

        <span class="eyebrow">
            RESERVACIÓN DE SERVICIOS
        </span>

        <h2 class="mt-3">
            Agenda una visita para tu mascota
        </h2>

        <p class="text-muted mt-2">
            Elige los servicios que necesita,
            selecciona una fecha y consulta
            los horarios disponibles.

            Tu solicitud será revisada
            por el encargado antes de confirmarse.
        </p>

    </div>

</div>

{{-- ==========================================
     FORMULARIO
========================================== --}}

<form
    action="{{ route('cliente.citas.store') }}"
    method="POST"
    id="appointment-create-form"
    data-appointment-form
    data-availability-url="{{ route('cliente.disponibilidad') }}"
>

    @csrf

    {{-- ======================================
         SELECCIONAR MASCOTA
    ====================================== --}}

    <div class="card mb-4">

        <div class="card-header">

            <h3>
                1. Selecciona tu mascota
            </h3>

        </div>

        <div class="card-body">

            <div class="form-group">

                <label for="pet_id" class="form-label">
                    Mascota *
                </label>

                <select
                    id="pet_id"
                    name="pet_id"
                    class="form-select @error('pet_id') is-invalid @enderror"
                    required
                >

                    <option value="">
                        Selecciona una mascota
                    </option>

                    @foreach($pets as $pet)

                        @if($pet->is_active)

                            <option
                                value="{{ $pet->id }}"
                                @selected((string) $selectedPet === (string) $pet->id)
                            >

                                {{ $pet->name }}

                                @if($pet->breed)
                                    - {{ $pet->breed }}
                                @endif

                            </option>

                        @endif

                    @endforeach

                </select>

                @error('pet_id')

                    <span class="form-error" role="alert">
                        {{ $message }}
                    </span>

                @enderror

                <p class="form-hint">
                    Solo puedes solicitar citas
                    para mascotas activas
                    registradas en tu cuenta.
                </p>

            </div>

            <a href="{{ route('cliente.mascotas.create') }}"
               class="btn btn-outline btn-sm">

                Registrar otra mascota

            </a>

        </div>

    </div>

    {{-- ======================================
         SELECCIONAR SERVICIOS
    ====================================== --}}

    <div class="card mb-4">

        <div class="card-header">

            <div>

                <h3>
                    2. Selecciona los servicios
                </h3>

                <p class="text-muted">
                    Puedes seleccionar uno o varios servicios.
                </p>

            </div>

        </div>

        <div class="card-body">

            @if($services->isNotEmpty())

                <div class="service-selector">

                    @foreach($services as $service)

                        <label class="service-option">

                            <input
                                type="checkbox"
                                name="services[]"
                                value="{{ $service->id }}"
                                data-price="{{ $service->price }}"
                                data-duration="{{ $service->duration_minutes }}"
                                @checked(in_array((string) $service->id, $selectedServices, true))
                            >

                            <span>

                                <strong>
                                    {{ $service->name }}
                                </strong>

                                @if($service->description)

                                    <span class="form-hint">
                                        {{ $service->description }}
                                    </span>

                                @endif

                                <span class="form-hint">

                                    {{ $service->duration_minutes }}
                                    minutos

                                </span>

                            </span>

                            <strong class="service-price">

                                ${{ number_format((float) $service->price, 2) }}

                            </strong>

                        </label>

                    @endforeach

                </div>

            @else

                <div class="empty-state">

                    <h3>
                        No hay servicios disponibles
                    </h3>

                    <p>
                        Por el momento no existen
                        servicios activos para solicitar.
                    </p>

                </div>

            @endif

            @error('services')

                <span class="form-error" role="alert">
                    {{ $message }}
                </span>

            @enderror

            @error('services.*')

                <span class="form-error" role="alert">
                    {{ $message }}
                </span>

            @enderror

        </div>

    </div>

    {{-- ======================================
         RESUMEN DE SERVICIOS
    ====================================== --}}

    <div class="card mb-4">

        <div class="card-header">

            <h3>
                Resumen de los servicios
            </h3>

        </div>

        <div class="card-body">

            <div class="detail-grid">

                <div class="detail-item">

                    <span class="detail-label">
                        Precio estimado
                    </span>

                    <strong
                        class="detail-value"
                        data-total-price
                    >
                        $0.00
                    </strong>

                </div>

                <div class="detail-item">

                    <span class="detail-label">
                        Duración estimada
                    </span>

                    <strong
                        class="detail-value"
                        data-total-duration
                    >
                        0 minutos
                    </strong>

                </div>

            </div>

            <p class="form-hint mt-3">
                El precio y la duración se calculan
                automáticamente según los servicios
                seleccionados.

                Los importes definitivos se validan
                en el servidor al registrar la cita.
            </p>

        </div>

    </div>

    {{-- ======================================
         FECHA
    ====================================== --}}

    <div class="card mb-4">

        <div class="card-header">

            <h3>
                3. Selecciona la fecha
            </h3>

        </div>

        <div class="card-body">

            <div class="form-group">

                <label
                    for="appointment_date"
                    class="form-label"
                >
                    Fecha de la cita *
                </label>

                <input
                    type="date"
                    id="appointment_date"
                    name="appointment_date"
                    class="form-control @error('appointment_date') is-invalid @enderror"
                    value="{{ old('appointment_date') }}"
                    min="{{ now()->toDateString() }}"
                    required
                >

                @error('appointment_date')

                    <span class="form-error" role="alert">
                        {{ $message }}
                    </span>

                @enderror

                <p class="form-hint">
                    Selecciona una fecha para consultar
                    los horarios disponibles.

                    Los días sin servicio no mostrarán
                    horarios para reservar.
                </p>

            </div>

            <a
                href="{{ route('horarios') }}"
                target="_blank"
                rel="noopener noreferrer"
                class="btn btn-outline btn-sm"
            >
                Consultar horarios de atención
            </a>

        </div>

    </div>

    {{-- ======================================
         HORARIOS DISPONIBLES
    ====================================== --}}

    <div class="card mb-4">

        <div class="card-header">

            <h3>
                4. Selecciona un horario
            </h3>

        </div>

        <div class="card-body">

            <p class="text-muted mb-4">
                Los horarios se mostrarán
                después de seleccionar
                al menos un servicio
                y una fecha.
            </p>

            <div
                class="time-slots"
                data-time-slots
                aria-live="polite"
            >

                <p class="text-muted">
                    Selecciona los servicios y la fecha
                    para consultar la disponibilidad.
                </p>

            </div>

            <input
                type="hidden"
                name="start_time"
                value="{{ old('start_time') }}"
            >

            @error('start_time')

                <span class="form-error" role="alert">
                    {{ $message }}
                </span>

            @enderror

            <p class="form-hint mt-3">
                El horario seleccionado debe permitir
                completar todos los servicios
                dentro del horario de atención.
            </p>

        </div>

    </div>

    {{-- ======================================
         OBSERVACIONES
    ====================================== --}}

    <div class="card mb-4">

        <div class="card-header">

            <h3>
                5. Información adicional
            </h3>

        </div>

        <div class="card-body">

            <div class="form-group">

                <label for="notes" class="form-label">
                    Observaciones para el encargado
                </label>

                <textarea
                    id="notes"
                    name="notes"
                    class="form-textarea @error('notes') is-invalid @enderror"
                    rows="5"
                    maxlength="1000"
                    placeholder="Ejemplo: Mi mascota se pone nerviosa con la secadora o necesita cuidados especiales."
                >{{ old('notes') }}</textarea>

                @error('notes')

                    <span class="form-error" role="alert">
                        {{ $message }}
                    </span>

                @enderror

                <p class="form-hint">
                    Este campo es opcional.
                    Puedes incluir información
                    importante para la atención
                    de tu mascota.
                </p>

            </div>

        </div>

    </div>

    {{-- ======================================
         CONFIRMACIÓN DE SOLICITUD
    ====================================== --}}

    <div class="card">

        <div class="card-header">

            <h3>
                Confirmar solicitud
            </h3>

        </div>

        <div class="card-body">

            <div class="alert alert-info">

                <p>
                    Al enviar este formulario,
                    tu cita quedará registrada
                    con estado pendiente.

                    El encargado deberá revisarla
                    y confirmarla antes de que
                    puedas considerarla aprobada.
                </p>

            </div>

            <div class="form-actions">

                <a
                    href="{{ route('cliente.citas.index') }}"
                    class="btn btn-secondary"
                >
                    Cancelar
                </a>

                <button
                    type="submit"
                    class="btn btn-primary"
                    id="submit-appointment-button"
                    @disabled($services->isEmpty() || $pets->where('is_active', true)->isEmpty())
                >
                    Enviar solicitud de cita
                </button>

            </div>

        </div>

    </div>

</form>

@endsection

@push('scripts')

<script>
    document.addEventListener('DOMContentLoaded', function () {

        const form = document.getElementById(
            'appointment-create-form'
        );

        const submitButton = document.getElementById(
            'submit-appointment-button'
        );

        if (!form || !submitButton) {
            return;
        }

        form.addEventListener('submit', function (event) {

            const selectedServices = form.querySelectorAll(
                'input[name="services[]"]:checked'
            );

            const selectedTime = form.querySelector(
                'input[name="start_time"]'
            );

            if (selectedServices.length === 0) {

                event.preventDefault();

                alert(
                    'Debes seleccionar al menos un servicio.'
                );

                return;
            }

            if (!selectedTime || !selectedTime.value) {

                event.preventDefault();

                alert(
                    'Debes seleccionar un horario disponible.'
                );

                return;
            }

            if (!form.checkValidity()) {
                return;
            }

            submitButton.disabled = true;

            submitButton.textContent =
                'Enviando solicitud...';

        });

    });
</script>

@endpush
