
@extends('layouts.public')

@section('title', 'Horarios de atención | Oh My Dog!')

@section('description', 'Consulta los días y horarios de atención de Oh My Dog! antes de solicitar una cita para tu mascota.')

@section('content')

{{-- ==========================================
     ENCABEZADO
========================================== --}}

<section class="section"
         style="background: var(--surface-alt);">

    <div class="container text-center">

        <span class="eyebrow">
            OH MY DOG!
        </span>

        <h1>
            Horarios de atención
        </h1>

        <p class="hero-description"
           style="margin: 18px auto 0;">

            Organiza tu visita y conoce nuestros
            días y horarios de atención.

            Estamos preparados para cuidar
            a tu mejor amigo.

        </p>

    </div>

</section>

{{-- ==========================================
     HORARIO SEMANAL
========================================== --}}

<section class="section">

    <div class="container">

        <div class="dashboard-grid">

            {{-- INFORMACIÓN --}}

            <div>

                <span class="eyebrow">
                    NUESTRA AGENDA
                </span>

                <h2>
                    ¿Cuándo puedes visitarnos?
                </h2>

                <p class="hero-description mt-3">

                    Consulta nuestros horarios
                    de atención y selecciona
                    una fecha adecuada para
                    solicitar tu cita.

                </p>

                <p class="text-muted mb-4">

                    Recuerda que las citas
                    están sujetas a disponibilidad
                    y deben ser confirmadas
                    por el encargado.

                </p>

                @auth

                    @if(auth()->user()->isEncargado())

                        <a href="{{ route('encargado.horarios.index') }}"
                           class="btn btn-primary">
                            Administrar horarios
                        </a>

                    @elseif(auth()->user()->is_active)

                        <a href="{{ route('cliente.citas.create') }}"
                           class="btn btn-primary">
                            Solicitar una cita
                        </a>

                    @else

                        <a href="{{ route('servicios') }}"
                           class="btn btn-primary">
                            Consultar servicios
                        </a>

                    @endif

                @else

                    <a href="{{ route('registro') }}"
                       class="btn btn-primary">
                        Registrarme para agendar
                    </a>

                @endauth

            </div>

            {{-- TABLA DE HORARIOS --}}

            <div class="card">

                <div class="card-header">

                    <h3>
                        Horario semanal
                    </h3>

                    <span class="badge badge-info">
                        Atención al público
                    </span>

                </div>

                <div class="card-body">

                    @forelse($schedules as $schedule)

                        <div class="list-item">

                            <div>

                                <div class="list-item-title">
                                    {{ $schedule->day_name }}
                                </div>

                                <p class="list-item-subtitle">

                                    @if($schedule->is_active)

                                        Horario de atención

                                    @else

                                        Sin atención al público

                                    @endif

                                </p>

                            </div>

                            <div class="text-right">

                                @if($schedule->is_active)

                                    <span class="badge badge-success">
                                        Abierto
                                    </span>

                                    <p class="text-muted mt-2">

                                        {{ $schedule->formatted_opening }}

                                        -

                                        {{ $schedule->formatted_closing }}

                                    </p>

                                @else

                                    <span class="badge badge-danger">
                                        Cerrado
                                    </span>

                                @endif

                            </div>

                        </div>

                    @empty

                        <div class="empty-state">

                            <h3>
                                Horarios no disponibles
                            </h3>

                            <p>
                                Todavía no se han
                                configurado los horarios
                                de atención.
                            </p>

                        </div>

                    @endforelse

                </div>

            </div>

        </div>

    </div>

</section>

{{-- ==========================================
     INFORMACIÓN SOBRE LAS CITAS
========================================== --}}

<section class="section"
         style="background: var(--surface-alt);">

    <div class="container">

        <div class="section-heading">

            <span class="eyebrow">
                INFORMACIÓN IMPORTANTE
            </span>

            <h2>
                Antes de agendar tu cita
            </h2>

            <p>
                Para ofrecer una atención
                organizada, nuestro sistema
                utiliza solicitudes de cita
                que revisa el encargado.
            </p>

        </div>

        <div class="service-grid">

            {{-- SELECCIÓN DE FECHA --}}

            <article class="card service-card">

                <div class="service-icon">

                    <svg class="icon icon-lg"
                         viewBox="0 0 24 24"
                         aria-hidden="true">

                        <rect x="3" y="5"
                              width="18" height="16"
                              rx="2"></rect>

                        <path d="M8 3v4
                                 M16 3v4
                                 M3 10h18">
                        </path>

                    </svg>

                </div>

                <h3>
                    Selecciona una fecha
                </h3>

                <p>
                    Elige un día de atención
                    y consulta los horarios
                    disponibles para los
                    servicios que necesitas.
                </p>

            </article>

            {{-- DURACIÓN --}}

            <article class="card service-card">

                <div class="service-icon">

                    <svg class="icon icon-lg"
                         viewBox="0 0 24 24"
                         aria-hidden="true">

                        <circle cx="12" cy="12"
                                r="9"></circle>

                        <path d="M12 7v5l3 2">
                        </path>

                    </svg>

                </div>

                <h3>
                    Duración del servicio
                </h3>

                <p>
                    Cada servicio tiene una
                    duración aproximada.

                    Si seleccionas varios,
                    el sistema sumará sus
                    tiempos para calcular
                    la duración total.
                </p>

            </article>

            {{-- CONFIRMACIÓN --}}

            <article class="card service-card">

                <div class="service-icon">

                    <svg class="icon icon-lg"
                         viewBox="0 0 24 24"
                         aria-hidden="true">

                        <path d="M20 6
                                 9 17
                                 4 12">
                        </path>

                    </svg>

                </div>

                <h3>
                    Espera la confirmación
                </h3>

                <p>
                    Después de enviar tu
                    solicitud, el encargado
                    revisará la disponibilidad
                    y podrá confirmar o
                    rechazar la cita.
                </p>

            </article>

        </div>

    </div>

</section>

{{-- ==========================================
     PREGUNTAS FRECUENTES
========================================== --}}

<section class="section">

    <div class="container">

        <div class="section-heading">

            <span class="eyebrow">
                PREGUNTAS FRECUENTES
            </span>

            <h2>
                ¿Tienes alguna duda?
            </h2>

        </div>

        <div class="dashboard-grid">

            <div class="card">

                <div class="card-body">

                    <h3>
                        ¿Necesito registrarme?
                    </h3>

                    <p class="text-muted mt-3">
                        Sí. Para solicitar una cita
                        necesitas crear una cuenta
                        e iniciar sesión como cliente.
                    </p>

                </div>

            </div>

            <div class="card">

                <div class="card-body">

                    <h3>
                        ¿Mi cita queda confirmada automáticamente?
                    </h3>

                    <p class="text-muted mt-3">
                        No. Primero se registra como
                        pendiente. El encargado
                        revisará la solicitud
                        antes de confirmarla.
                    </p>

                </div>

            </div>

            <div class="card">

                <div class="card-body">

                    <h3>
                        ¿Puedo solicitar varios servicios?
                    </h3>

                    <p class="text-muted mt-3">
                        Sí. Puedes seleccionar
                        varios servicios para
                        una misma mascota
                        dentro de una cita.
                    </p>

                </div>

            </div>

            <div class="card">

                <div class="card-body">

                    <h3>
                        ¿Puedo cancelar una cita?
                    </h3>

                    <p class="text-muted mt-3">
                        Sí. Puedes cancelar
                        una cita pendiente o
                        confirmada antes de
                        que comience, desde
                        tu panel de cliente.
                    </p>

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
            Agenda un momento especial
            para tu mascota
        </h2>

        <p style="max-width: 600px;
                  margin: 18px auto 28px;
                  color: #d4c6c2;">

            Consulta nuestros servicios
            y comienza a organizar
            la próxima visita de
            tu mejor amigo.

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
