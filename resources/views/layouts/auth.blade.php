
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <meta name="csrf-token"
          content="{{ csrf_token() }}">

    <meta name="description"
          content="@yield('description', 'Accede a Oh My Dog! para registrar tus mascotas y solicitar citas de estética canina.')">

    <title>
        @yield('title', 'Acceso | Oh My Dog!')
    </title>

    {{-- CONFIGURACIÓN INICIAL DEL TEMA --}}

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

    {{-- CSS Y JAVASCRIPT CON VITE --}}

    @vite(['resources/js/app.js'])

    @stack('styles')

</head>

<body>

    <main class="auth-page">

        <div class="auth-container">

            {{-- ======================================
                 LOGOTIPO
            ====================================== --}}

            <div class="auth-brand">

                <a href="{{ route('home') }}"
                   class="brand"
                   aria-label="Oh My Dog! - Ir al inicio">

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

            </div>

            {{-- ======================================
                 TARJETA DE AUTENTICACIÓN
            ====================================== --}}

            <section class="card auth-card"
                     aria-labelledby="auth-title">

                {{-- TÍTULO --}}

                <h1 class="auth-title"
                    id="auth-title">

                    @yield('auth-title', 'Bienvenido')

                </h1>

                {{-- SUBTÍTULO --}}

                <p class="auth-subtitle">

                    @yield(
                        'auth-subtitle',
                        'Accede a tu cuenta de Oh My Dog!'
                    )

                </p>

                {{-- ==================================
                     MENSAJES DE ÉXITO
                ================================== --}}

                @if(session('success'))

                    <div class="alert alert-success"
                         role="status">

                        {{ session('success') }}

                    </div>

                @endif

                {{-- ==================================
                     MENSAJES DE ERROR
                ================================== --}}

                @if(session('error'))

                    <div class="alert alert-danger"
                         role="alert">

                        {{ session('error') }}

                    </div>

                @endif

                {{-- ==================================
                     ERRORES DE VALIDACIÓN
                ================================== --}}

                @if($errors->any())

                    <div class="alert alert-danger"
                         role="alert">

                        <div>

                            <strong>
                                Revisa la información ingresada.
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

                    </div>

                @endif

                {{-- ==================================
                     FORMULARIO DE CADA PÁGINA
                ================================== --}}

                @yield('content')

                {{-- ==================================
                     ENLACES INFERIORES
                ================================== --}}

                <div class="auth-footer">

                    @yield('auth-footer')

                </div>

            </section>

            {{-- ======================================
                 CONTROLES INFERIORES
            ====================================== --}}

            <div class="flex items-center justify-between mt-4">

                <a href="{{ route('home') }}"
                   class="text-muted">

                    Volver al inicio

                </a>

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

            </div>

            {{-- DERECHOS --}}

            <p class="text-center text-muted mt-4"
               style="font-size: 12px;">

                &copy; {{ date('Y') }} Oh My Dog!

                Todos los derechos reservados.

            </p>

        </div>

    </main>

    @stack('scripts')

</body>
</html>
