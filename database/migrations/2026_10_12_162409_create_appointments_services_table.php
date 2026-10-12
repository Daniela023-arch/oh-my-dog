
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('appointments_services', function (Blueprint $table) {
            $table->id();

            // Cita a la que pertenece el servicio
            $table->foreignId('appointment_id')
                ->constrained('appointments')
                ->cascadeOnDelete();

            // Servicio solicitado
            $table->foreignId('service_id')
                ->constrained('services')
                ->restrictOnDelete();

            // Precio del servicio al solicitar la cita
            $table->decimal('price', 10, 2);

            // Duración del servicio al solicitar la cita
            $table->unsignedInteger('duration_minutes');

            $table->timestamps();

            // Evitar repetir un servicio en la misma cita
            $table->unique(
                ['appointment_id', 'service_id'],
                'appointment_service_unique'
            );

            // Optimizar consultas por servicio
            $table->index('service_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('appointments_services');
    }
};
