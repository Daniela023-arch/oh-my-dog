<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasMany;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * Campos permitidos para asignación masiva.
     */
    protected $fillable = [
        'name',
        'email',
        'phone',
        'password',
        'role',
        'is_active',
    ];

    /**
     * Campos que no deben mostrarse al convertir
     * el modelo en un arreglo o JSON.
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Conversión automática de tipos.
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_active' => 'boolean',
        ];
    }

    /**
     * Un cliente puede registrar varias mascotas.
     */
    public function pets(): HasMany
    {
        return $this->hasMany(Pet::class);
    }

    /**
     * Un cliente puede solicitar varias citas.
     */
    public function appointments(): HasMany
    {
        return $this->hasMany(Appointment::class);
    }

    /**
     * Citas gestionadas por el encargado.
     */
    public function managedAppointments(): HasMany
    {
        return $this->hasMany(
            Appointment::class,
            'managed_by'
        );
    }

    /**
     * Comprobar si el usuario es cliente.
     */
    public function isCliente(): bool
    {
        return $this->role === 'cliente';
    }

    /**
     * Comprobar si el usuario es encargado.
     */
    public function isEncargado(): bool
    {
        return $this->role === 'encargado';
    }

    /**
     * Comprobar si la cuenta está activa.
     */
    public function isActive(): bool
    {
        return $this->is_active;
    }
}
