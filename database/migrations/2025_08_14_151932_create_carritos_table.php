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
        Schema::create('carritos', function (Blueprint $table) {
            $table->id();
            $table->string('session_id');
            $table->unsignedBigInteger('empresa_id');
            $table->json('items');
            $table->decimal('subtotal', 12, 2)->default(0);
            $table->timestamp('ultima_actividad');
            $table->timestamps();
            
            $table->foreign('empresa_id')->references('id')->on('empresas')->onDelete('cascade');
            $table->index(['session_id', 'empresa_id']);
            $table->index('ultima_actividad');
        });
    }

    public function down()
    {
        Schema::dropIfExists('carritos');
    }
};
