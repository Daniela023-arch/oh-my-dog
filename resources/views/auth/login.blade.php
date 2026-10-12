
@extends('layouts.auth')

@section('title', 'Iniciar sesión | Oh My Dog!')

@section('description', 'Inicia sesión en Oh My Dog! para gestionar tus mascotas y citas de estética canina.')

@section('auth-title', 'Iniciar sesión')

@section('auth-subtitle', 'Bienvenido de nuevo. Ingresa tus datos para continuar.')

@section('content')

<form action="{{ route('login.procesar') }}"
      method="POST"
      id="login-form">

    @csrf

    {{-- =====================================
         CORREO ELECTRÓNICO
    ===================================== --}}

    <div class="form-group">

        <label for="email"
               class="form-label">
            Correo electrónico
        </label>

        <input
            type="email"
            id="email"
            name="email"
            class="form-control @error('email') is-invalid @enderror"
            value="{{ old('email') }}"
            placeholder="ejemplo@correo.com"
            autocomplete="username"
            maxlength="255"
            required
            autofocus
        >

        @error('email')

            <span class="form-error" role="alert">
                {{ $message }}
            </span>

        @enderror

    </div>

    {{-- =====================================
         CONTRASEÑA
    ===================================== --}}

    <div class="form-group">

        <label for="password"
               class="form-label">
            Contraseña
        </label>

        <div class="input-group">

            <input
                type="password"
                id="password"
                name="password"
                class="form-control @error('password') is-invalid @enderror"
                placeholder="Ingresa tu contraseña"
                autocomplete="current-password"
                required
            >

            <button
                type="button"
                class="btn btn-secondary btn-icon"
                id="toggle-password"
                aria-label="Mostrar contraseña"
                aria-controls="password"
                aria-pressed="false"
                title="Mostrar contraseña"
            >

                <svg class="icon"
                     viewBox="0 0 24 24"
                     fill="none"
                     stroke="currentColor"
                     stroke-width="2"
                     stroke-linecap="round"
                     stroke-linejoin="round"
                     aria-hidden="true">

                    <path d="M2 12s3.5-7 10-7 10 7 10 7-3.5 7-10 7S2 12 2 12Z">
                    </path>

                    <circle cx="12"
                            cy="12"
                            r="3">
                    </circle>

                </svg>

            </button>

        </div>

        @error('password')

            <span class="form-error" role="alert">
                {{ $message }}
            </span>

        @enderror

    </div>

    {{-- =====================================
         RECORDAR SESIÓN
    ===================================== --}}

    <div class="form-group">

        <label class="checkbox-label">

            <input
                type="checkbox"
                name="remember"
                value="1"
                {{ old('remember') ? 'checked' : '' }}
            >

            <span>
                Mantener mi sesión iniciada
            </span>

        </label>

    </div>

    {{-- =====================================
         BOTÓN DE INICIO DE SESIÓN
    ===================================== --}}

    <div class="form-actions">

        <button
            type="submit"
            class="btn btn-primary btn-lg btn-block"
            id="login-submit"
        >
            Iniciar sesión
        </button>

    </div>

</form>

@endsection

@section('auth-footer')

    <p>
        ¿Todavía no tienes una cuenta?

        <a href="{{ route('registro') }}">
            Regístrate aquí
        </a>
    </p>

@endsection

@push('scripts')

<script>
    document.addEventListener('DOMContentLoaded', function () {

        // Mostrar y ocultar contraseña.

        const passwordInput = document.getElementById('password');
        const toggleButton = document.getElementById('toggle-password');

        if (passwordInput && toggleButton) {

            toggleButton.addEventListener('click', function () {

                const isHidden = passwordInput.type === 'password';

                passwordInput.type = isHidden ? 'text' : 'password';

                toggleButton.setAttribute(
                    'aria-label',
                    isHidden ? 'Ocultar contraseña' : 'Mostrar contraseña'
                );

                toggleButton.setAttribute(
                    'title',
                    isHidden ? 'Ocultar contraseña' : 'Mostrar contraseña'
                );

                toggleButton.setAttribute(
                    'aria-pressed',
                    String(isHidden)
                );

            });

        }

        // Evitar envíos repetidos del formulario.

        const loginForm = document.getElementById('login-form');
        const submitButton = document.getElementById('login-submit');

        if (loginForm && submitButton) {

            loginForm.addEventListener('submit', function () {

                if (!loginForm.checkValidity()) {
                    return;
                }

                submitButton.disabled = true;
                submitButton.textContent = 'Iniciando sesión...';

            });

        }

    });
</script>

@endpush
