
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <meta name="csrf-token"
          content="{{ csrf_token() }}">

    <meta name="description"
          content="@yield('description', 'Oh My Dog! - Estética canina, baños, cortes y cuidado para tu mascota.')">

    <title>
        @yield('title', 'Oh My Dog!')
    </title>

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

    {{-- =========================================
         NAVEGACIÓN PÚBLICA
    ========================================= --}}

    <header class="public-header">

        <nav class="public-nav container"
             aria-label="Navegación principal">

            {{-- LOGOTIPO --}}

            <a href="{{ route('home') }}"
               class="brand"
               aria-label="Oh My Dog! - Inicio">

                <span class="brand-mark">

                    <svg class="icon"
                         viewBox="0 0 24 24"
                         aria-hidden="true">

                        <circle cx="7.5" cy="8"
                                r="1.2"></circle>

                        <circle cx="12" cy="5.5"
                                r="1.2"></circle>

                        <circle cx="16.5" cy="8"
                                r="1.2"></circle>

                        <circle cx="19" cy="12"
                                r="1.2"></circle>

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

            {{-- ENLACES DE NAVEGACIÓN --}}

            <div class="public-links"
                 id="public-navigation">

                <a href="{{ route('home') }}"
                   class="{{ request()->routeIs('home') ? 'active' : '' }}"
                   @if(request()->routeIs('home'))
                       aria-current="page"
                   @endif>
                    Inicio
                </a>

                <a href="{{ route('servicios') }}"
                   class="{{ request()->routeIs('servicios') ? 'active' : '' }}"
                   @if(request()->routeIs('servicios'))
                       aria-current="page"
                   @endif>
                    Servicios
                </a>

                <a href="{{ route('horarios') }}"
                   class="{{ request()->routeIs('horarios') ? 'active' : '' }}"
                   @if(request()->routeIs('horarios'))
                       aria-current="page"
                   @endif>
                    Horarios
                </a>

            </div>

            {{-- ACCIONES --}}

            <div class="nav-actions">

                {{-- CAMBIAR TEMA --}}

                <button type="button"
                        class="btn btn-secondary btn-icon"
                        data-theme-toggle
                        aria-label="Cambiar tema"
                        title="Cambiar tema">

                    <svg class="icon"
                         viewBox="0 0 24 24"
                         aria-hidden="true">

                        <circle cx="12" cy="12"
                                r="4"></circle>

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

                @auth

                    @if(auth()->user()->is_active)

                        @if(auth()->user()->role === 'encargado')

                            <a href="{{ route('encargado.dashboard') }}"
                               class="btn btn-primary">
                                Mi panel
                            </a>

                        @else

                            <a href="{{ route('cliente.dashboard') }}"
                               class="btn btn-primary">
                                Mi panel
                            </a>

                        @endif

                    @else

                        <form action="{{ route('logout') }}"
                              method="POST">
                            @csrf

                            <button type="submit"
                                    class="btn btn-secondary">
                                Cerrar sesión
                            </button>
                        </form>

                    @endif

                @else

                    <a href="{{ route('login') }}"
                       class="btn btn-primary">
                        Iniciar sesión
                    </a>

                @endauth

                {{-- BOTÓN DE MENÚ MÓVIL --}}

                <button type="button"
                        class="btn btn-secondary btn-icon mobile-menu-button"
                        data-public-menu-toggle
                        aria-controls="public-navigation"
                        aria-expanded="false"
                        aria-label="Abrir menú">

                    <svg class="icon"
                         viewBox="0 0 24 24"
                         aria-hidden="true">

                        <path d="M4 6h16
                                 M4 12h16
                                 M4 18h16">
                        </path>

                    </svg>

                </button>

            </div>

        </nav>

    </header>

    {{-- =========================================
         CONTENIDO PRINCIPAL
    ========================================= --}}

    <main id="main-content">

        {{-- MENSAJE DE ÉXITO --}}

        @if(session('success'))

            <div class="container mt-4">

                <div class="alert alert-success"
                     role="status"
                     data-auto-dismiss="5000">

                    {{ session('success') }}

                </div>

            </div>

        @endif

        {{-- MENSAJE DE ERROR --}}

        @if(session('error'))

            <div class="container mt-4">

                <div class="alert alert-danger"
                     role="alert">

                    {{ session('error') }}

                </div>

            </div>

        @endif

        {{-- CONTENIDO DE CADA PÁGINA --}}

        @yield('content')

    </main>

    {{-- =========================================
         PIE DE PÁGINA
    ========================================= --}}

    <footer class="public-footer">

        <div class="container">

            <div class="footer-grid">

                {{-- INFORMACIÓN DEL NEGOCIO --}}

                <div>

                    <h3 class="footer-title">
                        OH MY DOG!
                    </h3>

                    <p class="footer-description">

                        Cuidamos a tu mejor amigo con
                        cariño, dedicación y servicios
                        de estética canina.

                    </p>

                    <p class="footer-description mt-3">

                        Baños, cortes y cuidados
                        especiales para que tu mascota
                        se vea y se sienta increíble.

                    </p>

                </div>

                {{-- ENLACES --}}

                <div>

                    <h3 class="footer-title">
                        Explorar
                    </h3>

                    <ul class="footer-links">

                        <li>
                            <a href="{{ route('home') }}">
                                Inicio
                            </a>
                        </li>

                        <li>
                            <a href="{{ route('servicios') }}">
                                Servicios
                            </a>
                        </li>

                        <li>
                            <a href="{{ route('horarios') }}">
                                Horarios
                            </a>
                        </li>

                        <li>
                            <a href="{{ route('registro') }}">
                                Crear cuenta
                            </a>
                        </li>

                    </ul>

                </div>

                {{-- ATENCIÓN AL CLIENTE --}}

                <div>

                    <h3 class="footer-title">
                        Atención
                    </h3>

                    <ul class="footer-links">

                        <li>
                            <a href="{{ route('horarios') }}">
                                Consultar horarios
                            </a>
                        </li>

                        <li>
                            <a href="{{ route('servicios') }}">
                                Ver precios
                            </a>
                        </li>

                        <li>
                            <a href="{{ route('login') }}">
                                Solicitar una cita
                            </a>
                        </li>

                    </ul>

                </div>

            </div>

            {{-- DERECHOS --}}

            <div class="footer-bottom">

                <p>
                    &copy; {{ date('Y') }}
                    Oh My Dog!
                    Todos los derechos reservados.
                </p>

            </div>

        </div>

    </footer>

    @stack('scripts')

</body>
</html>
