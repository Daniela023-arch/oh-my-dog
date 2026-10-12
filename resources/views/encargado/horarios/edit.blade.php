
@extends('layouts.app')

@section('title', 'Editar horario | Oh My Dog!')

@section('content')

<div class="page-header">
    <div>
        <h1 class="page-title">Editar horario</h1>
        <p class="page-description">
            Configura el horario de atención para
            {{ strtolower($schedule->day_name) }}.
        </p>
    </div>

    <div class="page-actions">
        <a href="{{ route('encargado.horarios.index') }}"
           class="btn btn-outline">
            Volver a horarios
        </a>
    </div>
</div>

@if($errors->any())
    <div class="alert alert-danger">
        <strong>No se pudo actualizar el horario.</strong>

        <ul>
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div class="card">

    <div class="card-header">
        <h2>{{ $schedule->day_name }}</h2>

        @if($schedule->is_active)
            <span class="badge badge-success">
                Abierto
            </span>
        @else
            <span class="badge badge-danger">
                Cerrado
            </span>
        @endif
    </div>

    <div class="card-body">

        <form
            action="{{ route('encargado.horarios.update', $schedule->id) }}"
            method="POST"
        >
            @csrf
            @method('PUT')

            <div class="form-group">
                <label class="checkbox-label">
                    <input
                        type="checkbox"
                        name="is_active"
                        value="1"
                        {{ old('is_active', $schedule->is_active) ? 'checked' : '' }}
                    >

                    <span>
                        Abrir este día
                    </span>
                </label>

                <p class="form-hint">
                    Si desmarcas esta opción, el establecimiento
                    permanecerá cerrado durante este día.
                </p>
            </div>

            <div class="form-grid">

                <div class="form-group">
                    <label for="opening_time" class="form-label">
                        Hora de apertura
                    </label>

                    <input
                        type="time"
                        id="opening_time"
                        name="opening_time"
                        class="form-control @error('opening_time') is-invalid @enderror"
                        value="{{ old('opening_time', substr($schedule->opening_time, 0, 5)) }}"
                        required
                    >

                    @error('opening_time')
                        <span class="form-error">
                            {{ $message }}
                        </span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="closing_time" class="form-label">
                        Hora de cierre
                    </label>

                    <input
                        type="time"
                        id="closing_time"
                        name="closing_time"
                        class="form-control @error('closing_time') is-invalid @enderror"
                        value="{{ old('closing_time', substr($schedule->closing_time, 0, 5)) }}"
                        required
                    >

                    @error('closing_time')
                        <span class="form-error">
                            {{ $message }}
                        </span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="slot_minutes" class="form-label">
                        Intervalo entre citas
                    </label>

                    <select
                        id="slot_minutes"
                        name="slot_minutes"
                        class="form-control form-select @error('slot_minutes') is-invalid @enderror"
                        required
                    >
                        @foreach([15, 20, 30, 45, 60] as $minutes)
                            <option
                                value="{{ $minutes }}"
                                {{ (int) old('slot_minutes', $schedule->slot_minutes) === $minutes ? 'selected' : '' }}
                            >
                                {{ $minutes }} minutos
                            </option>
                        @endforeach
                    </select>

                    @error('slot_minutes')
                        <span class="form-error">
                            {{ $message }}
                        </span>
                    @enderror
                </div>

            </div>

            <div class="form-group">
                <label for="notes" class="form-label">
                    Notas del horario
                </label>

                <textarea
                    id="notes"
                    name="notes"
                    rows="4"
                    maxlength="500"
                    class="form-control form-textarea @error('notes') is-invalid @enderror"
                    placeholder="Información adicional sobre este horario..."
                >{{ old('notes', $schedule->notes) }}</textarea>

                @error('notes')
                    <span class="form-error">
                        {{ $message }}
                    </span>
                @enderror
            </div>

            <div class="alert alert-info">
                Los cambios de horario pueden afectar la disponibilidad
                de nuevas citas. El sistema comprobará las citas
                confirmadas antes de guardar cambios incompatibles.
            </div>

            <div class="form-actions">

                <a
                    href="{{ route('encargado.horarios.index') }}"
                    class="btn btn-outline"
                >
                    Cancelar
                </a>

                <button type="submit" class="btn btn-primary">
                    Guardar horario
                </button>

            </div>

        </form>

    </div>

</div>

@endsection
