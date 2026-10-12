
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <meta name="csrf-token"
          content="{{ csrf_token() }}">

    <meta name="description"
          content="Sistema de gestión de mascotas, citas y servicios de Oh My Dog!">

    <title>
        @yield('title', 'Panel | Oh My Dog!')
    </title>

    {{-- Aplicar el tema antes de mostrar la página --}}

    <script>
        (function () {
            try {
                const savedTheme = localStorage.getItem(
                    'ohmydog-theme'
                );

                const systemDark = window.matchMedia(
                    '(prefers-color-scheme: dark)'
                ).matches;

                const theme = savedTheme === 'light' ||
                    savedTheme === 'dark'
                    ? savedTheme
                    : (systemDark ? 'dark' : 'light');

                document.documentElement.setAttribute(
                    'data-theme',
                    theme
                );
            } catch (error) {
                document.documentElement.setAttribute(
                    'data-theme',
                    'light'
                );
            }
        })();
    </script>

    @vite(['resources/js/app.js'])

    @stack('styles')
</head>

<body>

@auth

@php
    $currentUser = auth()->user();

    $isManager = $currentUser->isEncargado();

    $dashboardRoute = $isManager
        ? 'encargado.dashboard'
        : 'cliente.dashboard';

    $initial = mb_strtoupper(
        mb_substr($currentUser->name, 0, 1)
    );
@endphp

<div class="app-layout">

    {{-- ==========================================
         MENÚ LATERAL
    ========================================== --}}

    <aside class="sidebar" id="app-sidebar">

        {{-- LOGOTIPO --}}

        <div class="sidebar-header">

            <a href="{{ route($dashboardRoute) }}"
               class="brand"
               aria-label="Oh My Dog! - Panel principal">

                <span class="brand-mark">

                    <svg class="icon"
                         viewBox="0 0 24 24"
                         aria-hidden="true">

                        <circle cx="7.5" cy="8" r="1.2"></circle>
                        <circle cx="12" cy="5.5" r="1.2"></circle>
                        <circle cx="16.5" cy="8" r="1.2"></circle>
                        <circle cx="19" cy="12" r="1.2"></circle>

                        <path d="M12 11.5
                                 C9.5 11.5 8.8 14 7 16
                                 C5.8 17.4 6.8 20 9 20
                                 C10.3 20 10.8 19.3 12 19.3
                                 C13.2 19.3 13.7 20 15 20
                                 C17.2 20 18.2 17.4 17 16
                                 C15.2 14 14.5 11.5 12 11.5Z">
                        </path>

                    </svg>

                </span>

                <span>OH MY DOG!</span>

            </a>

        </div>

        {{-- NAVEGACIÓN --}}

        <nav class="sidebar-nav"
             aria-label="Menú principal">

            <div class="sidebar-section-title">
                PRINCIPAL
            </div>

            {{-- DASHBOARD --}}

            <a href="{{ route($dashboardRoute) }}"
               class="sidebar-link {{ request()->routeIs($dashboardRoute) ? 'active' : '' }}"
               @if(request()->routeIs($dashboardRoute))
                   aria-current="page"
               @endif>

                <svg class="icon"
                     viewBox="0 0 24 24"
                     aria-hidden="true">

                    <rect x="3" y="3"
                          width="7" height="7"
                          rx="1"></rect>

                    <rect x="14" y="3"
                          width="7" height="7"
                          rx="1"></rect>

                    <rect x="3" y="14"
                          width="7" height="7"
                          rx="1"></rect>

                    <rect x="14" y="14"
                          width="7" height="7"
                          rx="1"></rect>

                </svg>

                <span>Dashboard</span>

            </a>

            {{-- ==================================
                 MENÚ DEL CLIENTE
            ================================== --}}

            @if(!$isManager)

                <div class="sidebar-section-title">
                    MIS GESTIONES
                </div>

                {{-- MASCOTAS --}}

                <a href="{{ route('cliente.mascotas.index') }}"
                   class="sidebar-link {{ request()->routeIs('cliente.mascotas.*') ? 'active' : '' }}">

                    <svg class="icon"
                         viewBox="0 0 24 24"
                         aria-hidden="true">

                        <circle cx="7.5" cy="8" r="1.2"></circle>
                        <circle cx="12" cy="5.5" r="1.2"></circle>
                        <circle cx="16.5" cy="8" r="1.2"></circle>

                        <path d="M12 12
                                 C9 12 7 15 7 17
                                 C7 19 9 20 12 19
                                 C15 20 17 19 17 17
                                 C17 15 15 12 12 12Z">
                        </path>

                    </svg>

                    <span>Mis mascotas</span>

                </a>

                {{-- CITAS --}}

                <a href="{{ route('cliente.citas.index') }}"
                   class="sidebar-link {{ request()->routeIs('cliente.citas.*') ? 'active' : '' }}">

                    <svg class="icon"
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

                    <span>Mis citas</span>

                </a>

                {{-- NUEVA CITA --}}

                <a href="{{ route('cliente.citas.create') }}"
                   class="sidebar-link {{ request()->routeIs('cliente.citas.create') ? 'active' : '' }}">

                    <svg class="icon"
                         viewBox="0 0 24 24"
                         aria-hidden="true">

                        <path d="M12 5v14
                                 M5 12h14">
                        </path>

                    </svg>

                    <span>Solicitar cita</span>

                </a>

            @endif

            {{-- ==================================
                 MENÚ DEL ENCARGADO
            ================================== --}}

            @if($isManager)

                <div class="sidebar-section-title">
                    ADMINISTRACIÓN
                </div>

                {{-- CITAS --}}

                <a href="{{ route('encargado.citas.index') }}"
                   class="sidebar-link {{ request()->routeIs('encargado.citas.*') ? 'active' : '' }}">

                    <svg class="icon"
                         viewBox="0 0 24 24"
                         aria-hidden="true">

                        <rect x="3" y="5"
                              width="18" height="16"
                              rx="2"></rect>

                        <path d="M8 3v4
                                 M16 3v4
                                 M3 10h18
                                 M9 16l2 2 4-4">
                        </path>

                    </svg>

                    <span>Gestión de citas</span>

                </a>

                {{-- SERVICIOS --}}

                <a href="{{ route('encargado.servicios.index') }}"
                   class="sidebar-link {{ request()->routeIs('encargado.servicios.*') ? 'active' : '' }}">

                    <svg class="icon"
                         viewBox="0 0 24 24"
                         aria-hidden="true">

                        <path d="M20 4L4 20
                                 M7 4l13 13">
                        </path>

                        <circle cx="5" cy="5" r="2"></circle>
                        <circle cx="19" cy="19" r="2"></circle>

                    </svg>

                    <span>Servicios</span>

                </a>

                {{-- HORARIOS --}}

                <a href="{{ route('encargado.horarios.index') }}"
                   class="sidebar-link {{ request()->routeIs('encargado.horarios.*') ? 'active' : '' }}">

                    <svg class="icon"
                         viewBox="0 0 24 24"
                         aria-hidden="true">

                        <circle cx="12" cy="12" r="9"></circle>

                        <path d="M12 7v5l3 2"></path>

                    </svg>

                    <span>Horarios</span>

                </a>

            @endif

            {{-- ==================================
                 ENLACES GENERALES
            ================================== --}}

            <div class="sidebar-section-title">
                SITIO WEB
            </div>

            <a href="{{ route('home') }}"
               class="sidebar-link">

                <svg class="icon"
                     viewBox="0 0 24 24"
                     aria-hidden="true">

                    <path d="M3 10l9-7 9 7
                             M5 9v12h14V9
                             M9 21v-8h6v8">
                    </path>

                </svg>

                <span>Ver sitio público</span>

            </a>

            <a href="{{ route('servicios') }}"
               class="sidebar-link">

                <svg class="icon"
                     viewBox="0 0 24 24"
                     aria-hidden="true">

                    <path d="M4 7h16
                             M4 12h16
                             M4 17h16">
                    </path>

                </svg>

                <span>Catálogo público</span>

            </a>

        </nav>

        {{-- ======================================
             USUARIO Y CIERRE DE SESIÓN
        ====================================== --}}

        <div class="sidebar-footer">

            <div class="sidebar-user">

                <div class="user-avatar">
                    {{ $initial }}
                </div>

                <div style="min-width: 0;">

                    <strong>
                        {{ $currentUser->name }}
                    </strong>

                    <p class="text-muted"
                       style="font-size: 12px;">

                        {{ $isManager ? 'Encargado' : 'Cliente' }}

                    </p>

                </div>

            </div>

            <form action="{{ route('logout') }}"
                  method="POST">

                @csrf

                <button type="submit"
                        class="btn btn-secondary btn-block">

                    <svg class="icon"
                         viewBox="0 0 24 24"
                         aria-hidden="true">

                        <path d="M10 17l5-5-5-5
                                 M15 12H3
                                 M12 3h7a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-7">
                        </path>

                    </svg>

                    Cerrar sesión

                </button>

            </form>

        </div>

    </aside>

    {{-- ==========================================
         CONTENIDO DERECHO
    ========================================== --}}

    <div class="main-wrapper">

        {{-- BARRA SUPERIOR --}}

        <header class="topbar">

            <div class="topbar-left">

                <button type="button"
                        class="btn btn-secondary btn-icon sidebar-toggle"
                        data-sidebar-toggle
                        aria-label="Abrir o cerrar menú"
                        aria-controls="app-sidebar"
                        aria-expanded="false">

                    <svg class="icon"
                         viewBox="0 0 24 24"
                         aria-hidden="true">

                        <path d="M4 6h16
                                 M4 12h16
                                 M4 18h16">
                        </path>

                    </svg>

                </button>

                <div>

                    <h2 class="topbar-title">
                        @yield('page-title', 'Dashboard')
                    </h2>

                    <p class="topbar-subtitle">
                        @yield('page-subtitle', 'Sistema de gestión Oh My Dog!')
                    </p>

                </div>

            </div>

            <div class="topbar-right">

                {{-- CAMBIO DE TEMA --}}

                <button type="button"
                        class="btn btn-secondary btn-icon"
                        data-theme-toggle
                        aria-label="Cambiar tema"
                        title="Cambiar tema">

                    <svg class="icon"
                         viewBox="0 0 24 24"
                         aria-hidden="true">

                        <circle cx="12" cy="12" r="4"></circle>

                        <path d="M12 2v2
                                 M12 20v2
                                 M4.93 4.93l1.42 1.42
                                 M17.65 17.65l1.42 1.42
                                 M2 12h2
                                 M20 12h2
                                 M4.93 19.07l1.42-1.42
                                 M17.65 6.35l1.42-1.42">
                        </path>

                    </svg>

                </button>

                <div class="user-avatar"
                     title="{{ $currentUser->name }}">
                    {{ $initial }}
                </div>

            </div>

        </header>

        {{-- ======================================
             CONTENIDO PRINCIPAL
        ====================================== --}}

        <main class="main-content">

            {{-- MENSAJES DE ÉXITO --}}

            @if(session('success'))

                <div class="alert alert-success"
                     role="status"
                     data-auto-dismiss="5000">

                    {{ session('success') }}

                </div>

            @endif

            {{-- MENSAJES DE ERROR --}}

            @if(session('error'))

                <div class="alert alert-danger"
                     role="alert">

                    {{ session('error') }}

                </div>

            @endif

            {{-- ERRORES DE VALIDACIÓN --}}

            @if($errors->any())

                <div class="alert alert-danger"
                     role="alert">

                    <strong>
                        Revisa los siguientes datos:
                    </strong>

                    <ul style="margin-top: 8px;
                               padding-left: 18px;
                               list-style: disc;">

                        @foreach($errors->all() as $error)

                            <li>
                                {{ $error }}
                            </li>

                        @endforeach

                    </ul>

                </div>

            @endif

            {{-- ENCABEZADO DE LA PÁGINA --}}

            <div class="page-header">

                <div>

                    <h1 class="page-title">
                        @yield('heading', 'Panel principal')
                    </h1>

                    <p class="page-description">
                        @yield('heading-description')
                    </p>

                </div>

                <div class="page-actions">
                    @yield('page-actions')
                </div>

            </div>

            {{-- CONTENIDO DE CADA VISTA --}}

            @yield('content')

        </main>

    </div>

</div>

@else

    {{-- Evitar mostrar información privada sin sesión --}}

    <main class="auth-page">

        <div class="auth-container">

            <div class="card auth-card text-center">

                <h1 class="auth-title">
                    Sesión requerida
                </h1>

                <p class="auth-subtitle">
                    Inicia sesión para acceder al sistema.
                </p>

                <a href="{{ route('login') }}"
                   class="btn btn-primary btn-block">
                    Iniciar sesión
                </a>

            </div>

        </div>

    </main>

@endauth

@stack('scripts')

</body>
</html>
