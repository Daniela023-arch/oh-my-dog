<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Carbon\Carbon;

class Schedule extends Model
{
    use HasFactory;

    /**
     * Nombre de la tabla.
     */
    protected $table = 'schedules';

    /**
     * Campos permitidos para asignación masiva.
     */
    protected $fillable = [
        'day_of_week',
        'opening_time',
        'closing_time',
        'slot_minutes',
        'is_active',
        'notes',
    ];

    /**
     * Conversión automática de tipos.
     */
    protected function casts(): array
    {
        return [
            'day_of_week' => 'integer',
            'slot_minutes' => 'integer',
            'is_active' => 'boolean',
        ];
    }

    /**
     * Nombres de los días de la semana.
     */
    public const DAYS = [
        0 => 'Domingo',
        1 => 'Lunes',
        2 => 'Martes',
        3 => 'Miércoles',
        4 => 'Jueves',
        5 => 'Viernes',
        6 => 'Sábado',
    ];

    /**
     * Obtener el nombre del día.
     */
    public function getDayNameAttribute(): string
    {
        return self::DAYS[$this->day_of_week]
            ?? 'Desconocido';
    }

    /**
     * Obtener la hora de apertura formateada.
     */
    public function getFormattedOpeningAttribute(): string
    {
        return Carbon::parse(
            $this->opening_time
        )->format('H:i');
    }

    /**
     * Obtener la hora de cierre formateada.
     */
    public function getFormattedClosingAttribute(): string
    {
        return Carbon::parse(
            $this->closing_time
        )->format('H:i');
    }

    /**
     * Consultar solamente días activos.
     */
    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    /**
     * Consultar horarios ordenados de lunes a domingo.
     */
    public function scopeWeekOrder(Builder $query): Builder
    {
        return $query->orderByRaw(
            'CASE WHEN day_of_week = 0 THEN 7
             ELSE day_of_week END'
        );
    }

    /**
     * Comprobar si el día está habilitado.
     */
    public function isActive(): bool
    {
        return $this->is_active;
    }

    /**
     * Obtener el horario correspondiente
     * a una fecha determinada.
     */
    public static function forDate(
        string $date
    ): ?self {
        $dayOfWeek = Carbon::parse(
            $date
        )->dayOfWeek;

        return self::where(
            'day_of_week',
            $dayOfWeek
        )->first();
    }

    /**
     * Comprobar si un intervalo está
     * dentro del horario de atención.
     */
    public function allowsTime(
        string $startTime,
        string $endTime
    ): bool {
        if (!$this->is_active) {
            return false;
        }

        $opening = Carbon::parse(
            $this->opening_time
        );

        $closing = Carbon::parse(
            $this->closing_time
        );

        $start = Carbon::parse($startTime);
        $end = Carbon::parse($endTime);

        return $start->greaterThanOrEqualTo($opening)
            && $end->lessThanOrEqualTo($closing)
            && $end->greaterThan($start);
    }

    /**
     * Generar intervalos disponibles
     * según la configuración del día.
     *
     * Este método genera horas posibles.
     * No descuenta citas confirmadas.
     */
    public function getTimeSlots(): array
    {
        if (!$this->is_active || $this->slot_minutes <= 0) {
            return [];
        }

        $slots = [];

        $current = Carbon::parse(
            $this->opening_time
        );

        $closing = Carbon::parse(
            $this->closing_time
        );

        while ($current->lessThan($closing)) {
            $slots[] = $current->format('H:i');

            $current->addMinutes(
                $this->slot_minutes
            );
        }

        return $slots;
    }
}
