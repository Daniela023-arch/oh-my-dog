
@extends('layouts.auth')

@section('title', 'Crear cuenta | Oh My Dog!')

@section('description', 'Regístrate en Oh My Dog! para administrar tus mascotas y solicitar citas de estética canina.')

@section('auth-title', 'Crear una cuenta')

@section('auth-subtitle', 'Regístrate para comenzar a cuidar a tu mejor amigo.')

@section('content')

<form action="{{ route('registro.procesar') }}"
      method="POST"
      id="register-form">

    @csrf

    {{-- =====================================
         NOMBRE COMPLETO
    ===================================== --}}

    <div class="form-group">

        <label for="name" class="form-label">
            Nombre completo
        </label>

        <input
            type="text"
            id="name"
            name="name"
            class="form-control @error('name') is-invalid @enderror"
            value="{{ old('name') }}"
            placeholder="Ingresa tu nombre completo"
            autocomplete="name"
            maxlength="255"
            required
            autofocus
        >

        @error('name')
            <span class="form-error" role="alert">
                {{ $message }}
            </span>
        @enderror

    </div>

    {{-- =====================================
         CORREO ELECTRÓNICO
    ===================================== --}}

    <div class="form-group">

        <label for="email" class="form-label">
            Correo electrónico
        </label>

        <input
            type="email"
            id="email"
            name="email"
            class="form-control @error('email') is-invalid @enderror"
            value="{{ old('email') }}"
            placeholder="ejemplo@correo.com"
            autocomplete="email"
            maxlength="255"
            required
        >

        @error('email')
            <span class="form-error" role="alert">
                {{ $message }}
            </span>
        @enderror

    </div>

    {{-- =====================================
         TELÉFONO
    ===================================== --}}

    <div class="form-group">

        <label for="phone" class="form-label">
            Número de teléfono
            <span class="text-muted">(opcional)</span>
        </label>

        <input
            type="tel"
            id="phone"
            name="phone"
            class="form-control @error('phone') is-invalid @enderror"
            value="{{ old('phone') }}"
            placeholder="Ejemplo: 5512345678"
            autocomplete="tel"
            maxlength="20"
        >

        @error('phone')
            <span class="form-error" role="alert">
                {{ $message }}
            </span>
        @enderror

    </div>

    {{-- =====================================
         CONTRASEÑA
    ===================================== --}}

    <div class="form-group">

        <label for="password" class="form-label">
            Contraseña
        </label>

        <div class="input-group">

            <input
                type="password"
                id="password"
                name="password"
                class="form-control @error('password') is-invalid @enderror"
                placeholder="Crea una contraseña segura"
                autocomplete="new-password"
                minlength="8"
                required
            >

            <button
                type="button"
                class="btn btn-secondary btn-icon"
                data-toggle-password="password"
                aria-label="Mostrar contraseña"
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

                    <circle cx="12" cy="12" r="3"></circle>

                </svg>

            </button>

        </div>

        <p class="form-hint">
            Utiliza al menos 8 caracteres.
        </p>

        @error('password')
            <span class="form-error" role="alert">
                {{ $message }}
            </span>
        @enderror

    </div>

    {{-- =====================================
         CONFIRMAR CONTRASEÑA
    ===================================== --}}

    <div class="form-group">

        <label for="password_confirmation"
               class="form-label">
            Confirmar contraseña
        </label>

        <div class="input-group">

            <input
                type="password"
                id="password_confirmation"
                name="password_confirmation"
                class="form-control"
                placeholder="Repite tu contraseña"
                autocomplete="new-password"
                minlength="8"
                required
            >

            <button
                type="button"
                class="btn btn-secondary btn-icon"
                data-toggle-password="password_confirmation"
                aria-label="Mostrar contraseña"
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

                    <circle cx="12" cy="12" r="3"></circle>

                </svg>

            </button>

        </div>

    </div>

    {{-- =====================================
         INFORMACIÓN DE REGISTRO
    ===================================== --}}

    <div class="alert alert-info">

        <p>
            Tu cuenta será registrada como cliente.
            Después podrás agregar tus mascotas
            y solicitar citas de estética canina.
        </p>

    </div>

    {{-- =====================================
         BOTÓN DE REGISTRO
    ===================================== --}}

    <div class="form-actions">

        <button
            type="submit"
            class="btn btn-primary btn-lg btn-block"
            id="register-submit"
        >
            Crear mi cuenta
        </button>

    </div>

</form>

@endsection

@section('auth-footer')

    <p>
        ¿Ya tienes una cuenta?

        <a href="{{ route('login') }}">
            Inicia sesión aquí
        </a>
    </p>

@endsection

@push('scripts')

<script>
    document.addEventListener('DOMContentLoaded', function () {

        // Mostrar u ocultar contraseñas.

        const toggleButtons = document.querySelectorAll(
            '[data-toggle-password]'
        );

        toggleButtons.forEach(function (button) {

            button.addEventListener('click', function () {

                const inputId = button.getAttribute(
                    'data-toggle-password'
                );

                const input = document.getElementById(inputId);

                if (!input) {
                    return;
                }

                const isHidden = input.type === 'password';

                input.type = isHidden ? 'text' : 'password';

                const label = isHidden
                    ? 'Ocultar contraseña'
                    : 'Mostrar contraseña';

                button.setAttribute('aria-label', label);
                button.setAttribute('title', label);
                button.setAttribute(
                    'aria-pressed',
                    String(isHidden)
                );

            });

        });

        // Verificar que ambas contraseñas coincidan.

        const password = document.getElementById('password');

        const confirmation = document.getElementById(
            'password_confirmation'
        );

        function validatePasswords() {

            if (!password || !confirmation) {
                return;
            }

            if (
                confirmation.value !== '' &&
                password.value !== confirmation.value
            ) {

                confirmation.setCustomValidity(
                    'Las contraseñas no coinciden.'
                );

            } else {

                confirmation.setCustomValidity('');

            }

        }

        if (password && confirmation) {

            password.addEventListener(
                'input',
                validatePasswords
            );

            confirmation.addEventListener(
                'input',
                validatePasswords
            );

        }

        // Evitar envíos duplicados.

        const registerForm = document.getElementById(
            'register-form'
        );

        const submitButton = document.getElementById(
            'register-submit'
        );

        if (registerForm && submitButton) {

            registerForm.addEventListener(
                'submit',
                function () {

                    validatePasswords();

                    if (!registerForm.checkValidity()) {
                        return;
                    }

                    submitButton.disabled = true;

                    submitButton.textContent =
                        'Creando cuenta...';

                }
            );

        }

    });
</script>

@endpush
