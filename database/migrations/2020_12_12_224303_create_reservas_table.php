<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateReservasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('reservas', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();
            $table->string('nome');
            $table->date('data');
            $table->string('horario_inicio');
            $table->string('horario_fim');
            $table->string('cor');
            $table->string('descricao');
            $table->foreignId('sala_id')->constrained('salas')->onDelete('cascade');
            # Campos para repetição semanal
            $table->unsignedBigInteger('parent_id')->nullable();
            $table->date('repeat_until')->nullable();
            $table->string('repeat_days')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('reservas');
    }
}
