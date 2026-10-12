
@extends('layouts.public')

@section('title', 'Nuestros servicios | Oh My Dog!')

@section('description', 'Conoce los servicios de estética canina de Oh My Dog!, sus precios y duración. Baños, cortes y cuidados para tu mascota.')

@section('content')

{{-- ==========================================
     ENCABEZADO DE SERVICIOS
========================================== --}}

<section class="section"
         style="background: var(--surface-alt);">

    <div class="container text-center">

        <span class="eyebrow">
            OH MY DOG!
        </span>

        <h1>
            Nuestros servicios
        </h1>

        <p class="hero-description"
           style="margin: 18px auto 0;">

            Cada mascota merece atención y cuidado.

            Explora nuestros servicios de estética
            canina y encuentra la opción ideal
            para tu mejor amigo.

        </p>

    </div>

</section>

{{-- ==========================================
     CATÁLOGO DE SERVICIOS
========================================== --}}

<section class="section">

    <div class="container">

        <div class="section-heading">

            <span class="eyebrow">
                CATÁLOGO
            </span>

            <h2>
                Cuidados para tu mascota
            </h2>

            <p>
                Consulta los servicios disponibles,
                sus precios y el tiempo aproximado
                de atención.
            </p>

        </div>

        @if($services->isNotEmpty())

            <div class="service-grid">

                @foreach($services as $service)

                    <article class="card service-card">

                        {{-- ICONO DEL SERVICIO --}}

                        <div class="service-icon">

                            <svg class="icon icon-lg"
                                 viewBox="0 0 24 24"
                                 aria-hidden="true">

                                <path d="M12 3
                                         C9 7 6 10 6 14
                                         a6 6 0 0 0 12 0
                                         C18 10 15 7 12 3Z">
                                </path>

                                <path d="M9 15
                                         a3 3 0 0 0 3 3">
                                </path>

                            </svg>

                        </div>

                        {{-- NOMBRE --}}

                        <h3>
                            {{ $service->name }}
                        </h3>

                        {{-- DESCRIPCIÓN --}}

                        <p>
                            {{ $service->description
                                ?: 'Servicio de estética y cuidado para tu mascota.' }}
                        </p>

                        {{-- PRECIO Y DURACIÓN --}}

                        <div class="flex items-center justify-between gap-3">

                            <div>

                                <div class="service-price">
                                    ${{ number_format((float) $service->price, 2) }}
                                </div>

                                <span class="service-duration">

                                    Duración aproximada:
                                    {{ $service->duration_minutes }}
                                    minutos

                                </span>

                            </div>

                        </div>

                        {{-- ACCIÓN --}}

                        <div class="mt-4">

                            @auth

                                @if(auth()->user()->is_active
                                    && auth()->user()->isCliente())

                                    <a
                                        href="{{ route('cliente.citas.create', ['service' => $service->id]) }}"
                                        class="btn btn-primary btn-block"
                                    >
                                        Solicitar este servicio
                                    </a>

                                @elseif(auth()->user()->isEncargado())

                                    <a
                                        href="{{ route('encargado.servicios.show', $service) }}"
                                        class="btn btn-secondary btn-block"
                                    >
                                        Administrar servicio
                                    </a>

                                @else

                                    <a
                                        href="{{ route('servicios') }}"
                                        class="btn btn-secondary btn-block"
                                    >
                                        Ver servicios
                                    </a>

                                @endif

                            @else

                                <a
                                    href="{{ route('registro') }}"
                                    class="btn btn-primary btn-block"
                                >
                                    Registrarme para agendar
                                </a>

                            @endauth

                        </div>

                    </article>

                @endforeach

            </div>

        @else

            {{-- SIN SERVICIOS DISPONIBLES --}}

            <div class="card empty-state">

                <div class="empty-state-icon">

                    <svg class="icon icon-lg"
                         viewBox="0 0 24 24"
                         aria-hidden="true">

                        <circle cx="7.5" cy="8" r="1"></circle>
                        <circle cx="12" cy="5.5" r="1"></circle>
                        <circle cx="16.5" cy="8" r="1"></circle>

                        <path d="M12 12
                                 C9 12 7 15 7 17
                                 C7 19 9 20 12 19
                                 C15 20 17 19 17 17
                                 C17 15 15 12 12 12Z">
                        </path>

                    </svg>

                </div>

                <h3>
                    No hay servicios disponibles
                </h3>

                <p>
                    Por el momento no contamos
                    con servicios activos.

                    Vuelve a consultar nuestro
                    catálogo próximamente.
                </p>

                <a href="{{ route('home') }}"
                   class="btn btn-primary">
                    Volver al inicio
                </a>

            </div>

        @endif

    </div>

</section>

{{-- ==========================================
     INFORMACIÓN IMPORTANTE
========================================== --}}

<section class="section"
         style="background: var(--surface-alt);">

    <div class="container">

        <div class="dashboard-grid">

            <div>

                <span class="eyebrow">
                    INFORMACIÓN IMPORTANTE
                </span>

                <h2>
                    Antes de solicitar tu cita
                </h2>

                <p class="hero-description mt-3">

                    Puedes seleccionar uno o varios
                    servicios para tu mascota.

                    El sistema calculará el precio
                    total y la duración aproximada
                    de la atención.

                </p>

                <p class="text-muted mb-4">

                    Todas las solicitudes están
                    sujetas a la disponibilidad
                    del establecimiento y a la
                    confirmación del encargado.

                </p>

                <a href="{{ route('horarios') }}"
                   class="btn btn-outline">
                    Consultar horarios
                </a>

            </div>

            <div class="card">

                <div class="card-header">

                    <h3>
                        ¿Cómo funciona?
                    </h3>

                </div>

                <div class="card-body">

                    <div class="list-item">

                        <div>

                            <div class="list-item-title">
                                1. Registra tu mascota
                            </div>

                            <p class="list-item-subtitle">
                                Agrega sus datos en tu cuenta.
                            </p>

                        </div>

                    </div>

                    <div class="list-item">

                        <div>

                            <div class="list-item-title">
                                2. Selecciona los servicios
                            </div>

                            <p class="list-item-subtitle">
                                Elige los cuidados que necesita.
                            </p>

                        </div>

                    </div>

                    <div class="list-item">

                        <div>

                            <div class="list-item-title">
                                3. Elige fecha y horario
                            </div>

                            <p class="list-item-subtitle">
                                Consulta las opciones disponibles.
                            </p>

                        </div>

                    </div>

                    <div class="list-item">

                        <div>

                            <div class="list-item-title">
                                4. Espera la confirmación
                            </div>

                            <p class="list-item-subtitle">
                                El encargado revisará tu solicitud.
                            </p>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>

</section>

{{-- ==========================================
     INVITACIÓN FINAL
========================================== --}}

<section class="section"
         style="background: var(--brown-dark);
                color: #ffffff;">

    <div class="container text-center">

        <span class="eyebrow"
              style="color: var(--gold);">
            AGENDA EN OH MY DOG!
        </span>

        <h2>
            Dale a tu mascota el cuidado que merece
        </h2>

        <p style="max-width: 620px;
                  margin: 18px auto 28px;
                  color: #d4c6c2;">

            Registra a tu mejor amigo, elige los
            servicios que necesita y envía
            tu solicitud de cita.

        </p>

        @auth

            @if(auth()->user()->isEncargado())

                <a href="{{ route('encargado.dashboard') }}"
                   class="btn btn-gold btn-lg">
                    Ir al panel
                </a>

            @elseif(auth()->user()->is_active)

                <a href="{{ route('cliente.citas.create') }}"
                   class="btn btn-gold btn-lg">
                    Solicitar una cita
                </a>

            @else

                <a href="{{ route('home') }}"
                   class="btn btn-gold btn-lg">
                    Volver al inicio
                </a>

            @endif

        @else

            <a href="{{ route('registro') }}"
               class="btn btn-gold btn-lg">
                Crear mi cuenta
            </a>

        @endauth

    </div>

</section>

@endsection

