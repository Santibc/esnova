<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('reacciones_calificaciones', function (Blueprint $table) {
            $table->id();
            $table->foreignId('calificacion_id')
                  ->constrained('calificaciones_productos')
                  ->onDelete('cascade');
            $table->string('visitor_id', 64);
            $table->enum('emoji', ['hearts', 'wink', 'kiss', 'thumbsup']);
            $table->timestamps();

            // Un visitante solo puede dar una reacción por tipo por reseña
            $table->unique(['calificacion_id', 'visitor_id', 'emoji'], 'reaccion_unica');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('reacciones_calificaciones');
    }
};
