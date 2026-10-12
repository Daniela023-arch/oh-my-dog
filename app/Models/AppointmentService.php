<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AppointmentService extends Model
{
    use HasFactory;

    /**
     * Nombre exacto de la tabla intermedia.
     */
    protected $table = 'appointments_services';

    /**
     * Campos permitidos para asignación masiva.
     */
    protected $fillable = [
        'appointment_id',
        'service_id',
        'price',
        'duration_minutes',
    ];

    /**
     * Conversión automática de tipos.
     */
    protected function casts(): array
    {
        return [
            'price' => 'decimal:2',
            'duration_minutes' => 'integer',
        ];
    }

    /**
     * Cita a la que pertenece el servicio.
     */
    public function appointment(): BelongsTo
    {
        return $this->belongsTo(
            Appointment::class,
            'appointment_id'
        );
    }

    /**
     * Servicio contratado.
     */
    public function service(): BelongsTo
    {
        return $this->belongsTo(
            Service::class,
            'service_id'
        );
    }

    /**
     * Obtener el precio formateado.
     */
    public function getFormattedPriceAttribute(): string
    {
        return '$' . number_format(
            (float) $this->price,
            2
        ) . ' MXN';
    }

    /**
     * Obtener la duración formateada.
     */
    public function getFormattedDurationAttribute(): string
    {
        $hours = intdiv($this->duration_minutes, 60);

        $minutes = $this->duration_minutes % 60;

        if ($hours > 0 && $minutes > 0) {
            return "{$hours} h {$minutes} min";
        }

        if ($hours > 0) {
            return "{$hours} h";
        }

        return "{$minutes} min";
    }
}
