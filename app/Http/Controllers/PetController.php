<?php

namespace App\Http\Controllers;

use App\Models\Pet;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class PetController extends Controller
{
    /**
     * Mostrar las mascotas del cliente.
     */
    public function index(Request $request): View
    {
        $pets = $request->user()
            ->pets()
            ->orderByDesc('is_active')
            ->orderBy('name')
            ->paginate(10);

        return view('cliente.mascotas.index', compact('pets'));
    }

    /**
     * Mostrar formulario para registrar mascota.
     */
    public function create(): View
    {
        return view('cliente.mascotas.create');
    }

    /**
     * Guardar una nueva mascota.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate(
            $this->validationRules()
        );

        $request->user()->pets()->create([
            ...$validated,
            'species' => 'Perro',
            'is_active' => true,
        ]);

        return redirect()
            ->route('cliente.mascotas.index')
            ->with(
                'success',
                'La mascota se registró correctamente.'
            );
    }

    /**
     * Mostrar detalles de una mascota.
     */
    public function show(Request $request, Pet $pet): View
    {
        $this->authorizeOwner($request, $pet);

        $pet->loadCount('appointments');

        return view(
            'cliente.mascotas.show',
            compact('pet')
        );
    }

    /**
     * Mostrar formulario de edición.
     */
    public function edit(Request $request, Pet $pet): View
    {
        $this->authorizeOwner($request, $pet);

        return view(
            'cliente.mascotas.edit',
            compact('pet')
        );
    }

    /**
     * Actualizar datos de una mascota.
     */
    public function update(
        Request $request,
        Pet $pet
    ): RedirectResponse {
        $this->authorizeOwner($request, $pet);

        $validated = $request->validate(
            $this->validationRules()
        );

        $pet->update([
            ...$validated,
            'species' => 'Perro',
        ]);

        return redirect()
            ->route('cliente.mascotas.show', $pet)
            ->with(
                'success',
                'Los datos de la mascota se actualizaron.'
            );
    }

    /**
     * Desactivar una mascota.
     */
    public function destroy(
        Request $request,
        Pet $pet
    ): RedirectResponse {
        $this->authorizeOwner($request, $pet);

        if (!$pet->is_active) {
            return back()->with(
                'info',
                'Esta mascota ya se encuentra desactivada.'
            );
        }

        // Impedir desactivar mascotas con citas futuras
        // pendientes o confirmadas.
        $hasUpcomingAppointments = $pet->appointments()
            ->whereIn('status', [
                'pendiente',
                'confirmada',
            ])
            ->whereDate(
                'appointment_date',
                '>=',
                today()->toDateString()
            )
            ->exists();

        if ($hasUpcomingAppointments) {
            return back()->withErrors([
                'pet' => 'No puedes desactivar esta mascota porque tiene citas futuras pendientes o confirmadas.',
            ]);
        }

        $pet->update([
            'is_active' => false,
        ]);

        return redirect()
            ->route('cliente.mascotas.index')
            ->with(
                'success',
                'La mascota se desactivó correctamente.'
            );
    }

    /**
     * Reactivar una mascota.
     */
    public function activate(
        Request $request,
        Pet $pet
    ): RedirectResponse {
        $this->authorizeOwner($request, $pet);

        if ($pet->is_active) {
            return back()->with(
                'info',
                'Esta mascota ya se encuentra activa.'
            );
        }

        $pet->update([
            'is_active' => true,
        ]);

        return redirect()
            ->route('cliente.mascotas.index')
            ->with(
                'success',
                'La mascota se reactivó correctamente.'
            );
    }

    /**
     * Validaciones para crear y editar mascotas.
     */
    private function validationRules(): array
    {
        return [
            'name' => [
                'required',
                'string',
                'max:120',
            ],
            'breed' => [
                'nullable',
                'string',
                'max:120',
            ],
            'sex' => [
                'nullable',
                Rule::in(['macho', 'hembra']),
            ],
            'birth_date' => [
                'nullable',
                'date',
                'before_or_equal:today',
            ],
            'weight' => [
                'nullable',
                'numeric',
                'gt:0',
                'max:9999.99',
            ],
            'notes' => [
                'nullable',
                'string',
                'max:2000',
            ],
        ];
    }

    /**
     * Verificar que la mascota pertenezca
     * al usuario autenticado.
     */
    private function authorizeOwner(
        Request $request,
        Pet $pet
    ): void {
        abort_unless(
            $pet->user_id === $request->user()->id,
            403,
            'No tienes permiso para acceder a esta mascota.'
        );
    }
}
