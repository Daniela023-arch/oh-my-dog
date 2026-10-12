
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('services', function (Blueprint $table) {
            $table->id();

            // Información del servicio
            $table->string('name', 120)->unique();

            $table->text('description')->nullable();

            // Precio del servicio en pesos mexicanos
            $table->decimal('price', 10, 2);

            // Duración estimada en minutos
            $table->unsignedInteger('duration_minutes');

            // Estado de disponibilidad
            $table->boolean('is_active')->default(true);

            // Fechas de registro y actualización
            $table->timestamps();

            // Índice para consultar servicios activos
            $table->index('is_active');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('services');
    }
};
