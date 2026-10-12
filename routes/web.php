
<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AuthController;
use App\Http\Controllers\PetController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\ScheduleController;
use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\DashboardController;

use App\Models\Service;
use App\Models\Schedule;

/*
|--------------------------------------------------------------------------
| OH MY DOG! - RUTAS PUBLICAS
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    $services = Service::active()
        ->orderBy('name')
        ->get();

    $schedules = Schedule::weekOrder()->get();

    return view('public.home', compact('services', 'schedules'));
})->name('home');

Route::get('/servicios', [ServiceController::class, 'catalog'])
    ->name('servicios');

Route::get('/horarios', [ScheduleController::class, 'publicHours'])
    ->name('horarios');

/*
|--------------------------------------------------------------------------
| AUTENTICACION
|--------------------------------------------------------------------------
*/

Route::middleware('guest')->group(function () {

    Route::get('/login', [AuthController::class, 'showLogin'])
        ->name('login');

    Route::post('/login', [AuthController::class, 'login'])
        ->name('login.procesar');

    Route::get('/registro', [AuthController::class, 'showRegister'])
        ->name('registro');

    Route::post('/registro', [AuthController::class, 'register'])
        ->name('registro.procesar');
});

Route::post('/logout', [AuthController::class, 'logout'])
    ->middleware('auth')
    ->name('logout');

/*
|--------------------------------------------------------------------------
| RUTAS DEL CLIENTE
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'role:cliente'])
    ->prefix('cliente')
    ->name('cliente.')
    ->group(function () {

        // Dashboard
        Route::get('/dashboard', [DashboardController::class, 'client'])
            ->name('dashboard');

        // Mascotas
        Route::get('/mascotas', [PetController::class, 'index'])
            ->name('mascotas.index');

        Route::get('/mascotas/crear', [PetController::class, 'create'])
            ->name('mascotas.create');

        Route::post('/mascotas', [PetController::class, 'store'])
            ->name('mascotas.store');

        Route::get('/mascotas/{pet}', [PetController::class, 'show'])
            ->name('mascotas.show');

        Route::get('/mascotas/{pet}/editar', [PetController::class, 'edit'])
            ->name('mascotas.edit');

        Route::put('/mascotas/{pet}', [PetController::class, 'update'])
            ->name('mascotas.update');

        Route::delete('/mascotas/{pet}', [PetController::class, 'destroy'])
            ->name('mascotas.destroy');

        Route::patch('/mascotas/{pet}/activar', [PetController::class, 'activate'])
            ->name('mascotas.activate');

        // Citas del cliente
        Route::get('/citas', [AppointmentController::class, 'index'])
            ->name('citas.index');

        Route::get('/citas/crear', [AppointmentController::class, 'create'])
            ->name('citas.create');

        Route::post('/citas', [AppointmentController::class, 'store'])
            ->name('citas.store');

        Route::get('/citas/{appointment}', [AppointmentController::class, 'show'])
            ->name('citas.show');

        Route::patch('/citas/{appointment}/cancelar', [AppointmentController::class, 'cancel'])
            ->name('citas.cancel');

        // Disponibilidad de horarios
        Route::get('/disponibilidad', [ScheduleController::class, 'availability'])
            ->name('disponibilidad');
    });

/*
|--------------------------------------------------------------------------
| RUTAS DEL ENCARGADO
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'role:encargado'])
    ->prefix('encargado')
    ->name('encargado.')
    ->group(function () {

        // Dashboard
        Route::get('/dashboard', [DashboardController::class, 'manager'])
            ->name('dashboard');

        // Servicios
        Route::get('/servicios', [ServiceController::class, 'index'])
            ->name('servicios.index');

        Route::get('/servicios/crear', [ServiceController::class, 'create'])
            ->name('servicios.create');

        Route::post('/servicios', [ServiceController::class, 'store'])
            ->name('servicios.store');

        Route::get('/servicios/{service}', [ServiceController::class, 'show'])
            ->name('servicios.show');

        Route::get('/servicios/{service}/editar', [ServiceController::class, 'edit'])
            ->name('servicios.edit');

        Route::put('/servicios/{service}', [ServiceController::class, 'update'])
            ->name('servicios.update');

        Route::delete('/servicios/{service}', [ServiceController::class, 'destroy'])
            ->name('servicios.destroy');

        Route::patch('/servicios/{service}/activar', [ServiceController::class, 'activate'])
            ->name('servicios.activate');

        // Horarios
        Route::get('/horarios', [ScheduleController::class, 'index'])
            ->name('horarios.index');

        Route::get('/horarios/{schedule}/editar', [ScheduleController::class, 'edit'])
            ->name('horarios.edit');

        Route::put('/horarios/{schedule}', [ScheduleController::class, 'update'])
            ->name('horarios.update');

        // Citas del encargado
        Route::get('/citas', [AppointmentController::class, 'managerIndex'])
            ->name('citas.index');

        Route::get('/citas/{appointment}', [AppointmentController::class, 'managerShow'])
            ->name('citas.show');

        Route::patch('/citas/{appointment}/confirmar', [AppointmentController::class, 'confirm'])
            ->name('citas.confirm');

        Route::patch('/citas/{appointment}/rechazar', [AppointmentController::class, 'reject'])
            ->name('citas.reject');

        Route::patch('/citas/{appointment}/atender', [AppointmentController::class, 'attend'])
            ->name('citas.attend');

        Route::patch('/citas/{appointment}/cancelar', [AppointmentController::class, 'managerCancel'])
            ->name('citas.cancel');
    });
