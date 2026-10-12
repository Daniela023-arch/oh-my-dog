
@extends('layouts.app')

@section('title', 'Mis mascotas | Oh My Dog!')

@section('page-title', 'Mis mascotas')

@section('page-subtitle', 'Administración de mascotas registradas')

@section('heading', 'Mis mascotas')

@section('heading-description', 'Consulta y administra las mascotas asociadas a tu cuenta.')

@section('page-actions')

    <a href="{{ route('cliente.mascotas.create') }}"
       class="btn btn-primary">

        <svg class="icon"
             viewBox="0 0 24 24"
             aria-hidden="true">

            <path d="M12 5v14 M5 12h14"></path>

        </svg>

        Registrar mascota

    </a>

@endsection

@section('content')

{{-- ==========================================
     RESUMEN
========================================== --}}

<div class="card mb-4">

    <div class="card-body">

        <span class="eyebrow">
            MIS COMPAÑEROS
        </span>

        <h2 class="mt-3">
            Las mascotas de mi familia
        </h2>

        <p class="text-muted mt-2">
            Mantén actualizada la información
            de tus mascotas para facilitar
            la organización de sus citas
            y servicios de estética canina.
        </p>

    </div>

</div>

{{-- ==========================================
     LISTADO DE MASCOTAS
========================================== --}}

@if($pets->isNotEmpty())

    <div class="pet-grid">

        @foreach($pets as $pet)

            <article class="pet-card">

                {{-- ICONO --}}

                <div class="pet-avatar">

                    <svg class="icon icon-lg"
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

                </div>

                {{-- NOMBRE --}}

                <h3 class="pet-name">
                    {{ $pet->name }}
                </h3>

                {{-- ESPECIE Y RAZA --}}

                <p class="pet-meta">

                    {{ $pet->species }}

                    @if($pet->breed)
                        · {{ $pet->breed }}
                    @endif

                </p>

                {{-- ESTADO --}}

                <div class="mt-3">

                    @if($pet->is_active)

                        <span class="badge badge-success">
                            Activa
                        </span>

                    @else

                        <span class="badge badge-neutral">
                            Inactiva
                        </span>

                    @endif

                </div>

                {{-- INFORMACIÓN ADICIONAL --}}

                <div class="mt-4">

                    <div class="list-item">

                        <span class="text-muted">
                            Sexo
                        </span>

                        <strong>

                            @if($pet->sex === 'macho')
                                Macho
                            @elseif($pet->sex === 'hembra')
                                Hembra
                            @else
                                No especificado
                            @endif

                        </strong>

                    </div>

                    <div class="list-item">

                        <span class="text-muted">
                            Edad
                        </span>

                        <strong>
                            {{ $pet->age ?? 'No registrada' }}
                        </strong>

                    </div>

                    <div class="list-item">

                        <span class="text-muted">
                            Peso
                        </span>

                        <strong>

                            @if($pet->weight !== null)

                                {{ number_format((float) $pet->weight, 2) }}
                                kg

                            @else

                                No registrado

                            @endif

                        </strong>

                    </div>

                </div>

                {{-- ACCIONES --}}

                <div class="pet-actions mt-4">

                    <a href="{{ route('cliente.mascotas.show', $pet) }}"
                       class="btn btn-secondary btn-sm">

                        Ver detalles

                    </a>

                    <a href="{{ route('cliente.mascotas.edit', $pet) }}"
                       class="btn btn-outline btn-sm">

                        Editar

                    </a>

                </div>

                <div class="mt-3">

                    @if($pet->is_active)

                        <form
                            action="{{ route('cliente.mascotas.destroy', $pet) }}"
                            method="POST"
                            data-confirm="¿Deseas desactivar esta mascota? No podrás desactivarla si tiene citas futuras pendientes o confirmadas."
                        >

                            @csrf
                            @method('DELETE')

                            <button type="submit"
                                    class="btn btn-danger btn-sm btn-block">

                                Desactivar mascota

                            </button>

                        </form>

                    @else

                        <form
                            action="{{ route('cliente.mascotas.activate', $pet) }}"
                            method="POST"
                        >

                            @csrf
                            @method('PATCH')

                            <button type="submit"
                                    class="btn btn-primary btn-sm btn-block">

                                Reactivar mascota

                            </button>

                        </form>

                    @endif

                </div>

            </article>

        @endforeach

    </div>

    {{-- ======================================
         PAGINACIÓN
    ====================================== --}}

    @if(method_exists($pets, 'hasPages') && $pets->hasPages())

        <div class="pagination mt-4">

            {{ $pets->links() }}

        </div>

    @endif

@else

    {{-- ======================================
         SIN MASCOTAS
    ====================================== --}}

    <div class="card">

        <div class="empty-state">

            <div class="pet-avatar">

                <svg class="icon icon-lg"
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

            </div>

            <h3 class="mt-3">
                Todavía no tienes mascotas registradas
            </h3>

            <p>
                Registra a tu primera mascota
                para comenzar a solicitar
                citas de estética canina.
            </p>

            <a href="{{ route('cliente.mascotas.create') }}"
               class="btn btn-primary mt-4">

                Registrar mi primera mascota

            </a>

        </div>

    </div>

@endif

{{-- ==========================================
     INFORMACIÓN IMPORTANTE
========================================== --}}

<div class="card mt-4">

    <div class="card-body">

        <h3>
            Información importante
        </h3>

        <p class="text-muted mt-3">

            Puedes registrar varias mascotas
            dentro de tu cuenta.

            Cada mascota tendrá su propio
            historial de citas y servicios.

        </p>

        <p class="text-muted mt-3">

            Si desactivas una mascota,
            conservarás su información,
            pero no podrás utilizarla
            para solicitar nuevas citas
            hasta que la reactives.

        </p>

    </div>

</div>

@endsection
