<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Service extends Model
{
    use HasFactory;

    /**
     * Nombre de la tabla.
     */
    protected $table = 'services';

    /**
     * Campos permitidos para asignación masiva.
     */
    protected $fillable = [
        'name',
        'description',
        'price',
        'duration_minutes',
        'is_active',
    ];

    /**
     * Conversión automática de tipos.
     */
    protected function casts(): array
    {
        return [
            'price' => 'decimal:2',
            'duration_minutes' => 'integer',
            'is_active' => 'boolean',
        ];
    }

    /**
     * Un servicio puede pertenecer a varias citas.
     *
     * Utilizamos la tabla intermedia
     * appointments_services.
     */
    public function appointments(): BelongsToMany
    {
        return $this->belongsToMany(
            Appointment::class,
            'appointments_services',
            'service_id',
            'appointment_id'
        )
        ->withPivot([
            'id',
            'price',
            'duration_minutes',
        ])
        ->withTimestamps();
    }

    /**
     * Filtrar solamente los servicios activos.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Comprobar si el servicio está disponible.
     */
    public function isActive(): bool
    {
        return $this->is_active;
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
     * Obtener la duración en formato legible.
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
