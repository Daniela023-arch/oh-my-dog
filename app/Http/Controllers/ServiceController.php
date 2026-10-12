<?php

namespace App\Http\Controllers;

use App\Models\Service;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class ServiceController extends Controller
{
    /**
     * Mostrar el catálogo público de servicios activos.
     */
    public function catalog(): View
    {
        $services = Service::query()
            ->active()
            ->orderBy('name')
            ->get();

        return view('public.servicios', compact('services'));
    }

    /**
     * Mostrar todos los servicios al encargado.
     */
    public function index(Request $request): View
    {
        $this->authorizeManager($request);

        $query = Service::query();

        // Filtrar por estado.
        $status = $request->query('status');

        if ($status === 'active') {
            $query->where('is_active', true);
        } elseif ($status === 'inactive') {
            $query->where('is_active', false);
        }

        // Buscar por nombre.
        $search = trim(
            (string) $request->query('search', '')
        );

        if ($search !== '') {
            $query->where(
                'name',
                'like',
                '%' . $search . '%'
            );
        }

        $services = $query
            ->orderByDesc('is_active')
            ->orderBy('name')
            ->paginate(10)
            ->withQueryString();

        return view(
            'encargado.servicios.index',
            compact('services')
        );
    }

    /**
     * Mostrar formulario para registrar un servicio.
     */
    public function create(Request $request): View
    {
        $this->authorizeManager($request);

        return view('encargado.servicios.create');
    }

    /**
     * Guardar un nuevo servicio.
     */
    public function store(Request $request): RedirectResponse
    {
        $this->authorizeManager($request);

        $validated = $request->validate(
            $this->validationRules()
        );

        Service::create([
            ...$validated,
            'is_active' => true,
        ]);

        return redirect()
            ->route('encargado.servicios.index')
            ->with(
                'success',
                'El servicio se registró correctamente.'
            );
    }

    /**
     * Mostrar detalles de un servicio.
     */
    public function show(
        Request $request,
        Service $service
    ): View {
        $this->authorizeManager($request);

        return view(
            'encargado.servicios.show',
            compact('service')
        );
    }

    /**
     * Mostrar formulario de edición.
     */
    public function edit(
        Request $request,
        Service $service
    ): View {
        $this->authorizeManager($request);

        return view(
            'encargado.servicios.edit',
            compact('service')
        );
    }

    /**
     * Actualizar los datos de un servicio.
     */
    public function update(
        Request $request,
        Service $service
    ): RedirectResponse {
        $this->authorizeManager($request);

        $validated = $request->validate(
            $this->validationRules($service)
        );

        $service->update($validated);

        return redirect()
            ->route('encargado.servicios.index')
            ->with(
                'success',
                'El servicio se actualizó correctamente.'
            );
    }

    /**
     * Desactivar un servicio.
     */
    public function destroy(
        Request $request,
        Service $service
    ): RedirectResponse {
        $this->authorizeManager($request);

        if (!$service->is_active) {
            return back()->with(
                'info',
                'Este servicio ya se encuentra desactivado.'
            );
        }

        $service->update([
            'is_active' => false,
        ]);

        return redirect()
            ->route('encargado.servicios.index')
            ->with(
                'success',
                'El servicio se desactivó correctamente.'
            );
    }

    /**
     * Reactivar un servicio.
     */
    public function activate(
        Request $request,
        Service $service
    ): RedirectResponse {
        $this->authorizeManager($request);

        if ($service->is_active) {
            return back()->with(
                'info',
                'Este servicio ya se encuentra activo.'
            );
        }

        $service->update([
            'is_active' => true,
        ]);

        return redirect()
            ->route('encargado.servicios.index')
            ->with(
                'success',
                'El servicio se reactivó correctamente.'
            );
    }

    /**
     * Validaciones para crear y editar servicios.
     */
    private function validationRules(
        ?Service $service = null
    ): array {
        $nameRule = Rule::unique(
            'services',
            'name'
        );

        if ($service !== null) {
            $nameRule->ignore($service->id);
        }

        return [
            'name' => [
                'required',
                'string',
                'max:120',
                $nameRule,
            ],
            'description' => [
                'nullable',
                'string',
                'max:2000',
            ],
            'price' => [
                'required',
                'numeric',
                'min:0',
                'max:99999999.99',
                'decimal:0,2',
            ],
            'duration_minutes' => [
                'required',
                'integer',
                'min:1',
                'max:1440',
            ],
        ];
    }

    /**
     * Permitir administración solo al encargado.
     */
    private function authorizeManager(
        Request $request
    ): void {
        $user = $request->user();

        abort_unless(
            $user !== null
                && $user->is_active
                && $user->role === 'encargado',
            403,
            'No tienes permiso para administrar servicios.'
        );
    }
}
