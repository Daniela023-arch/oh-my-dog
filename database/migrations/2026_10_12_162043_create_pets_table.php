
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pets', function (Blueprint $table) {
            $table->id();

            // Cliente propietario de la mascota
            $table->foreignId('user_id')
                ->constrained('users')
                ->cascadeOnDelete();

            // Información de la mascota
            $table->string('name', 100);
            $table->string('species', 50)->default('Perro');
            $table->string('breed', 100)->nullable();

            $table->enum('sex', [
                'macho',
                'hembra'
            ])->nullable();

            $table->date('birth_date')->nullable();

            $table->decimal('weight', 6, 2)->nullable();

            // Información adicional
            $table->text('notes')->nullable();

            $table->boolean('is_active')->default(true);

            $table->timestamps();

            // Índices para consultas
            $table->index('name');
            $table->index('is_active');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pets');
    }
};
