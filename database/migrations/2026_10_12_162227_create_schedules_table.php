
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('schedules', function (Blueprint $table) {
            $table->id();

            // Día de la semana:
            // 0 = Domingo, 1 = Lunes, ..., 6 = Sábado
            $table->unsignedTinyInteger('day_of_week');

            // Horario de apertura y cierre
            $table->time('opening_time');
            $table->time('closing_time');

            // Duración de los intervalos para solicitar citas
            $table->unsignedInteger('slot_minutes')
                ->default(30);

            // Indica si el día está disponible
            $table->boolean('is_active')->default(true);

            // Observaciones del horario
            $table->string('notes', 255)->nullable();

            $table->timestamps();

            // Solo un registro por día de la semana
            $table->unique('day_of_week');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('schedules');
    }
};
