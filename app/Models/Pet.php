<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Pet extends Model
{
    use HasFactory;

    /**
     * Nombre de la tabla.
     */
    protected $table = 'pets';

    /**
     * Campos permitidos para asignación masiva.
     */
    protected $fillable = [
        'user_id',
        'name',
        'species',
        'breed',
        'sex',
        'birth_date',
        'weight',
        'notes',
        'is_active',
    ];

    /**
     * Conversión automática de tipos.
     */
    protected function casts(): array
    {
        return [
            'birth_date' => 'date',
            'weight' => 'decimal:2',
            'is_active' => 'boolean',
        ];
    }

    /**
     * Cada mascota pertenece a un usuario.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Una mascota puede tener varias citas.
     */
    public function appointments(): HasMany
    {
        return $this->hasMany(Appointment::class);
    }

    /**
     * Comprobar si la mascota está activa.
     */
    public function isActive(): bool
    {
        return $this->is_active;
    }

    /**
     * Obtener el nombre de la mascota.
     */
    public function getDisplayNameAttribute(): string
    {
        return $this->name;
    }

    /**
     * Obtener la edad aproximada en años.
     */
    public function getAgeAttribute(): ?int
    {
        if (!$this->birth_date) {
            return null;
        }

        return $this->birth_date->diffInYears(
            now()
        );
    }
}
