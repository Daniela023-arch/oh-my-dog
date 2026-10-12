
@extends('layouts.public')

@section('title', 'Inicio | Oh My Dog!')

@section('description', 'Oh My Dog! Estética canina con servicios de baño, corte y cuidado para tu mascota. Consulta nuestros servicios y agenda una cita.')

@section('content')

{{-- ==========================================
     PRESENTACIÓN PRINCIPAL
========================================== --}}

<section class="hero">

    <div class="container hero-grid">

        <div>

            <span class="eyebrow">
                ESTÉTICA CANINA
            </span>

            <h1 class="hero-title">
                Porque ellos merecen
                <span>lo mejor.</span>
            </h1>

            <p class="hero-description">
                En Oh My Dog! cuidamos a tu mejor amigo
                con cariño, paciencia y atención.

                Ofrecemos servicios de baño, corte
                y estética canina para que tu mascota
                luzca increíble y se sienta cómoda.
            </p>

            <div class="hero-actions">

                @auth

                    @if(auth()->user()->is_active)

                        <a
                            href="{{ auth()->user()->isEncargado()
                                ? route('encargado.dashboard')
                                : route('cliente.citas.create') }}"
                            class="btn btn-primary btn-lg"
                        >
                            {{ auth()->user()->isEncargado()
                                ? 'Ir a mi panel'
                                : 'Agendar una cita' }}
                        </a>

                    @else

                        <a href="{{ route('logout') }}"
                           class="btn btn-secondary btn-lg"
                           onclick="event.preventDefault(); document.getElementById('home-logout-form').submit();">
                            Cerrar sesión
                        </a>

                        <form id="home-logout-form"
                              action="{{ route('logout') }}"
                              method="POST"
                              hidden>
                            @csrf
                        </form>

                    @endif

                @else

                    <a href="{{ route('registro') }}"
                       class="btn btn-primary btn-lg">
                        Agendar una cita
                    </a>

                @endauth

                <a href="{{ route('servicios') }}"
                   class="btn btn-outline btn-lg">
                    Conocer servicios
                </a>

            </div>

        </div>

        <div class="hero-image">

            <img
                src="https://images.unsplash.com/photo-1516734212186-a967f81ad0d7?auto=format&fit=crop&w=1200&q=85"
                alt="Perro recibiendo cuidados de estética canina"
                loading="eager"
            >

        </div>

    </div>

</section>

{{-- ==========================================
     PRESENTACIÓN DEL NEGOCIO
========================================== --}}

<section class="section"
         style="background: var(--surface-alt);">

    <div class="container">

        <div class="section-heading">

            <span class="eyebrow">
                BIENVENIDO A OH MY DOG!
            </span>

            <h2>
                Un espacio pensado para tu mascota
            </h2>

            <p>
                Queremos que cada visita sea una
                experiencia agradable, segura y
                llena de cuidados.
            </p>

        </div>

        <div class="service-grid">

            {{-- ATENCIÓN PERSONALIZADA --}}

            <article class="card service-card">

                <div class="service-icon">

                    <svg class="icon icon-lg"
                         viewBox="0 0 24 24"
                         aria-hidden="true">

                        <path d="M20.8 4.6a5.5 5.5 0 0 0-7.8 0L12 5.7l-1.1-1.1a5.5 5.5 0 0 0-7.8 7.8L12 21l8.8-8.6a5.5 5.5 0 0 0 0-7.8Z">
                        </path>

                    </svg>

                </div>

                <h3>
                    Atención con cariño
                </h3>

                <p>
                    Tratamos a cada mascota con
                    paciencia, respeto y dedicación,
                    procurando su bienestar durante
                    todo el servicio.
                </p>

            </article>

            {{-- SERVICIOS --}}

            <article class="card service-card">

                <div class="service-icon">

                    <svg class="icon icon-lg"
                         viewBox="0 0 24 24"
                         aria-hidden="true">

                        <circle cx="6" cy="6" r="3"></circle>
                        <circle cx="6" cy="18" r="3"></circle>

                        <path d="M8.1 8.1 21 21
                                 M14 10 21 3
                                 M8.1 15.9 11 13">
                        </path>

                    </svg>

                </div>

                <h3>
                    Servicios de estética
                </h3>

                <p>
                    Contamos con opciones de baño,
                    corte, limpieza y cuidado
                    adaptadas a las necesidades
                    de tu mejor amigo.
                </p>

            </article>

            {{-- CITAS --}}

            <article class="card service-card">

                <div class="service-icon">

                    <svg class="icon icon-lg"
                         viewBox="0 0 24 24"
                         aria-hidden="true">

                        <rect x="3" y="5"
                              width="18" height="16"
                              rx="2"></rect>

                        <path d="M16 3v4
                                 M8 3v4
                                 M3 10h18
                                 M9 16l2 2 4-4">
                        </path>

                    </svg>

                </div>

                <h3>
                    Agenda tus citas
                </h3>

                <p>
                    Registra a tus mascotas,
                    selecciona los servicios que
                    necesitas y solicita una cita
                    desde nuestro sistema web.
                </p>

            </article>

        </div>

    </div>

</section>

{{-- ==========================================
     SERVICIOS DESTACADOS
========================================== --}}

<section class="section" id="servicios">

    <div class="container">

        <div class="section-heading">

            <span class="eyebrow">
                NUESTROS SERVICIOS
            </span>

            <h2>
                Todo lo que tu mascota necesita
            </h2>

            <p>
                Conoce algunos de nuestros servicios
                de estética canina y encuentra
                la mejor opción para tu mascota.
            </p>

        </div>

        @if($services->isNotEmpty())

            <div class="service-grid">

                @foreach($services as $service)

                    <article class="card service-card">

                        <div class="service-icon">

                            <svg class="icon icon-lg"
                                 viewBox="0 0 24 24"
                                 aria-hidden="true">

                                <path d="M12 3c-2 3-6 7-6 11a6 6 0 0 0 12 0c0-4-4-8-6-11Z">
                                </path>

                                <path d="M9 15a3 3 0 0 0 3 3">
                                </path>

                            </svg>

                        </div>

                        <h3>
                            {{ $service->name }}
                        </h3>

                        <p>
                            {{ $service->description
                                ?: 'Servicio de cuidado y estética para tu mascota.' }}
                        </p>

                        <div class="flex items-center justify-between gap-3">

                            <div>

                                <div class="service-price">
                                    ${{ number_format((float) $service->price, 2) }}
                                </div>

                                <span class="service-duration">
                                    {{ $service->duration_minutes }}
                                    minutos
                                </span>

                            </div>

                            <a href="{{ route('servicios') }}"
                               class="btn btn-secondary btn-sm">
                                Ver más
                            </a>

                        </div>

                    </article>

                @endforeach

            </div>

        @else

            <div class="card empty-state">

                <h3>
                    Próximamente tendremos servicios
                </h3>

                <p>
                    Estamos preparando nuestro catálogo
                    de estética canina.
                </p>

            </div>

        @endif

        <div class="text-center mt-4">

            <a href="{{ route('servicios') }}"
               class="btn btn-primary btn-lg">
                Ver todos los servicios
            </a>

        </div>

    </div>

</section>

{{-- ==========================================
     CÓMO FUNCIONA
========================================== --}}

<section class="section"
         style="background: var(--surface-alt);">

    <div class="container">

        <div class="section-heading">

            <span class="eyebrow">
                FÁCIL Y RÁPIDO
            </span>

            <h2>
                ¿Cómo solicitar una cita?
            </h2>

            <p>
                En unos cuantos pasos podrás enviar
                una solicitud para el cuidado
                de tu mascota.
            </p>

        </div>

        <div class="service-grid">

            <article class="card service-card">

                <span class="service-price">
                    01
                </span>

                <h3 class="mt-3">
                    Crea tu cuenta
                </h3>

                <p>
                    Regístrate como cliente
                    para acceder al sistema
                    de citas.
                </p>

            </article>

            <article class="card service-card">

                <span class="service-price">
                    02
                </span>

                <h3 class="mt-3">
                    Registra tu mascota
                </h3>

                <p>
                    Agrega sus datos para
                    identificarla y asociarla
                    a tus citas.
                </p>

            </article>

            <article class="card service-card">

                <span class="service-price">
                    03
                </span>

                <h3 class="mt-3">
                    Solicita una cita
                </h3>

                <p>
                    Elige los servicios, la fecha
                    y el horario. El encargado
                    revisará tu solicitud
                    y podrá confirmarla.
                </p>

            </article>

        </div>

    </div>

</section>

{{-- ==========================================
     HORARIOS DE ATENCIÓN
========================================== --}}

<section class="section" id="horarios">

    <div class="container">

        <div class="dashboard-grid">

            <div>

                <span class="eyebrow">
                    HORARIOS DE ATENCIÓN
                </span>

                <h2>
                    Encuentra el momento ideal
                    para tu mascota
                </h2>

                <p class="hero-description mt-3">
                    Consulta nuestros horarios de
                    atención antes de solicitar
                    una cita.

                    La disponibilidad puede variar
                    según los servicios seleccionados
                    y las citas confirmadas.
                </p>

                <a href="{{ route('horarios') }}"
                   class="btn btn-primary">
                    Consultar horarios
                </a>

            </div>

            <div class="card">

                <div class="card-header">

                    <h3>
                        Horario semanal
                    </h3>

                </div>

                <div class="card-body">

                    @forelse($schedules as $schedule)

                        <div class="list-item">

                            <span class="list-item-title">
                                {{ $schedule->day_name }}
                            </span>

                            @if($schedule->is_active)

                                <span class="text-muted">
                                    {{ $schedule->formatted_opening }}
                                    -
                                    {{ $schedule->formatted_closing }}
                                </span>

                            @else

                                <span class="badge badge-neutral">
                                    Cerrado
                                </span>

                            @endif

                        </div>

                    @empty

                        <p class="text-muted">
                            Los horarios todavía
                            no están disponibles.
                        </p>

                    @endforelse

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
            OH MY DOG!
        </span>

        <h2>
            Tu mejor amigo merece
            un cuidado especial
        </h2>

        <p style="max-width: 600px;
                  margin: 18px auto 28px;
                  color: #d4c6c2;">

            Descubre nuestros servicios y
            comienza a disfrutar de una forma
            sencilla de organizar las citas
            de tu mascota.

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

                <a href="{{ route('servicios') }}"
                   class="btn btn-gold btn-lg">
                    Ver servicios
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
