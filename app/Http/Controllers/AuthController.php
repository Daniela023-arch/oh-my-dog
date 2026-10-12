<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Illuminate\View\View;

class AuthController extends Controller
{
    /**
     * Mostrar el formulario de inicio de sesión.
     */
    public function showLogin(): View|RedirectResponse
    {
        if (Auth::check()) {
            return $this->redirectByRole();
        }

        return view('auth.login');
    }

    /**
     * Procesar el inicio de sesión.
     */
    public function login(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'email' => [
                'required',
                'email',
            ],
            'password' => [
                'required',
                'string',
            ],
        ]);

        $remember = $request->boolean('remember');

        $credentials['is_active'] = true;

        if (!Auth::attempt($credentials, $remember)) {
            return back()
                ->withErrors([
                    'email' => 'El correo, la contraseña o el estado de la cuenta no son válidos.',
                ])
                ->onlyInput('email');
        }

        // Evitar la fijación de sesión.
        $request->session()->regenerate();

        return $this->redirectByRole();
    }

    /**
     * Mostrar el formulario de registro.
     */
    public function showRegister(): View|RedirectResponse
    {
        if (Auth::check()) {
            return $this->redirectByRole();
        }

        return view('auth.register');
    }

    /**
     * Registrar un nuevo cliente.
     */
    public function register(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:120',
            ],
            'email' => [
                'required',
                'email',
                'max:150',
                'unique:users,email',
            ],
            'phone' => [
                'nullable',
                'string',
                'max:20',
            ],
            'password' => [
                'required',
                'confirmed',
                Password::min(8)
                    ->letters()
                    ->numbers(),
            ],
        ]);

        $user = DB::transaction(function () use ($validated) {
            return User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'phone' => $validated['phone'] ?? null,
                'password' => Hash::make(
                    $validated['password']
                ),
                'role' => 'cliente',
                'is_active' => true,
            ]);
        });

        Auth::login($user);

        $request->session()->regenerate();

        return redirect()
            ->route('cliente.dashboard')
            ->with(
                'success',
                '¡Tu cuenta se creó correctamente!'
            );
    }

    /**
     * Cerrar sesión.
     */
    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect()
            ->route('home')
            ->with(
                'success',
                'Has cerrado sesión correctamente.'
            );
    }

    /**
     * Redirigir según el rol del usuario.
     */
    private function redirectByRole(): RedirectResponse
    {
        $user = Auth::user();

        if (!$user) {
            return redirect()->route('login');
        }

        if (!$user->is_active) {
            Auth::logout();

            return redirect()
                ->route('login')
                ->withErrors([
                    'email' => 'Tu cuenta se encuentra desactivada.',
                ]);
        }

        if ($user->role === 'encargado') {
            return redirect()
                ->route('encargado.dashboard');
        }

        if ($user->role === 'cliente') {
            return redirect()
                ->route('cliente.dashboard');
        }

        Auth::logout();

        return redirect()
            ->route('login')
            ->withErrors([
                'email' => 'Tu cuenta no tiene un rol válido.',
            ]);
    }
}
