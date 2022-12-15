<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRecursoSalaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('recurso_sala', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('recurso_id');
            $table->unsignedBigInteger('sala_id');
            $table->foreign('recurso_id')->references('id')
                  ->on('recursos')->onDelete('cascade');
            $table->foreign('sala_id')->references('id')
                  ->on('salas')->onDelete('cascade');
            $table->timestamps();

        });
    }

    /**
     * Reverse the migrations.
     *                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                               
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('recurso_sala');
    }
}
