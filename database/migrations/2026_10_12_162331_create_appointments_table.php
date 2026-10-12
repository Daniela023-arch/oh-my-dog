
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('appointments', function (Blueprint $table) {
            $table->id();

            // Cliente que solicita la cita
            $table->foreignId('user_id')
                ->constrained('users')
                ->restrictOnDelete();

            // Mascota que recibirá el servicio
            $table->foreignId('pet_id')
                ->constrained('pets')
                ->restrictOnDelete();

            // Fecha y horario de la cita
            $table->date('appointment_date');
            $table->time('start_time');
            $table->time('end_time');

            // Estado de la cita
            $table->enum('status', [
                'pendiente',
                'confirmada',
                'rechazada',
                'cancelada',
                'atendida'
            ])->default('pendiente');

            // Observaciones del cliente
            $table->text('notes')->nullable();

            // Motivo de rechazo o cancelación
            $table->text('rejection_reason')->nullable();

            // Encargado que gestionó la cita
            $table->foreignId('managed_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            // Fecha de decisión del encargado
            $table->timestamp('reviewed_at')->nullable();

            // Información de la atención realizada
            $table->text('attention_notes')->nullable();
            $table->timestamp('attended_at')->nullable();

            // Fecha de cancelación
            $table->timestamp('cancelled_at')->nullable();

            $table->timestamps();

            // Índices para consultas
            $table->index('appointment_date');
            $table->index('status');

            $table->index([
                'appointment_date',
                'start_time',
                'end_time',
                'status'
            ], 'appointments_availability_index');

            $table->index([
                'user_id',
                'status'
            ]);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('appointments');
    }
};
