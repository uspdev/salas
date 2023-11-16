<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePeriodosLetivosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('periodos_letivos')) {
            Schema::create('periodos_letivos', function (Blueprint $table) {
                $table->id();
                $table->string('codigo')->unique;
                $table->date('data_inicio'); // data inicial do período letivo
                $table->date('data_fim'); // data final do período letivo
                $table->date('data_inicio_reservas'); // data inicial para realizar reservas neste período letivo
                $table->date('data_fim_reservas'); // data final para realizar reservas neste período letivo
                $table->timestamps();
            });
        }
    }



    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('periodos_letivos');
    }
}
