<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Appointment extends Model
{
    use HasFactory;

    /**
     * Nombre de la tabla.
     */
    protected $table = 'appointments';

    /**
     * Campos permitidos para asignación masiva.
     */
    protected $fillable = [
        'user_id',
        'pet_id',
        'appointment_date',
        'start_time',
        'end_time',
        'status',
        'notes',
        'rejection_reason',
        'managed_by',
        'reviewed_at',
        'attention_notes',
        'attended_at',
        'cancelled_at',
    ];

    /**
     * Conversión automática de tipos.
     */
    protected function casts(): array
    {
        return [
            'appointment_date' => 'date',
            'reviewed_at' => 'datetime',
            'attended_at' => 'datetime',
            'cancelled_at' => 'datetime',
        ];
    }

    /**
     * Cliente que solicitó la cita.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Mascota que recibirá los servicios.
     */
    public function pet(): BelongsTo
    {
        return $this->belongsTo(Pet::class);
    }

    /**
     * Encargado que gestionó la solicitud.
     */
    public function manager(): BelongsTo
    {
        return $this->belongsTo(
            User::class,
            'managed_by'
        );
    }

    /**
     * Servicios asociados a la cita.
     */
    public function services(): BelongsToMany
    {
        return $this->belongsToMany(
            Service::class,
            'appointments_services',
            'appointment_id',
            'service_id'
        )
        ->withPivot([
            'id',
            'price',
            'duration_minutes',
        ])
        ->withTimestamps();
    }

    /**
     * Consultar citas pendientes.
     */
    public function scopePending(Builder $query): Builder
    {
        return $query->where('status', 'pendiente');
    }

    /**
     * Consultar citas confirmadas.
     */
    public function scopeConfirmed(Builder $query): Builder
    {
        return $query->where('status', 'confirmada');
    }

    /**
     * Consultar citas atendidas.
     */
    public function scopeAttended(Builder $query): Builder
    {
        return $query->where('status', 'atendida');
    }

    /**
     * Comprobar si está pendiente.
     */
    public function isPending(): bool
    {
        return $this->status === 'pendiente';
    }

    /**
     * Comprobar si está confirmada.
     */
    public function isConfirmed(): bool
    {
        return $this->status === 'confirmada';
    }

    /**
     * Comprobar si está atendida.
     */
    public function isAttended(): bool
    {
        return $this->status === 'atendida';
    }

    /**
     * Comprobar si el cliente puede cancelar.
     */
    public function canBeCancelled(): bool
    {
        return $this->isPending();
    }

    /**
     * Comprobar si el encargado puede decidir.
     */
    public function canBeReviewed(): bool
    {
        return $this->isPending();
    }

    /**
     * Comprobar si puede registrarse como atendida.
     */
    public function canBeAttended(): bool
    {
        return $this->isConfirmed();
    }

    /**
     * Calcular el precio total de los servicios.
     */
    public function getTotalPriceAttribute(): float
    {
        $services = $this->services;

        return round(
            (float) $services->sum(
                fn ($service) => (float) $service->pivot->price
            ),
            2
        );
    }

    /**
     * Calcular la duración total en minutos.
     */
    public function getTotalDurationAttribute(): int
    {
        $services = $this->services;

        return (int) $services->sum(
            fn ($service) => (int) $service->pivot->duration_minutes
        );
    }

    /**
     * Obtener el precio formateado.
     */
    public function getFormattedTotalAttribute(): string
    {
        return '$' . number_format(
            $this->total_price,
            2
        ) . ' MXN';
    }

    /**
     * Consultar citas confirmadas que se traslapan
     * con un intervalo de tiempo.
     */
    public function scopeOverlapping(
        Builder $query,
        string $date,
        string $startTime,
        string $endTime
    ): Builder {
        return $query
            ->whereDate('appointment_date', $date)
            ->where('status', 'confirmada')
            ->where('start_time', '<', $endTime)
            ->where('end_time', '>', $startTime);
    }
}
