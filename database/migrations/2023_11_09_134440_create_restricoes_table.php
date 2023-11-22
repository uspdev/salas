<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRestricoesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('restricoes')) {
            Schema::create('restricoes', function (Blueprint $table) {
                $table->id();
                $table->foreignId('sala_id')->constrained()->onDelete('cascade');
                $table->enum('tipo_restricao', ['NENHUMA', 'AUTO', 'FIXA', 'PERIODO_LETIVO'])->default('NENHUMA'); // AUTO: a data limite é calculada automaticamente com base no dia corrente, FIXADA: a data limite é determinada manualmente, PERIODO_LETIVO: as datas de início e limite são definidas no período letivo
                $table->date('data_limite')->nullable(); // Data limite para reserva
                $table->integer('dias_limite')->nullable(); // Número de dias limite para reserva
                $table->integer('dias_antecedencia')->nullable(); // Número de dias de antecedência minima para reserva
                $table->foreignId('periodo_letivo_id')->nullable() // Período letivo
                    ->constrained('periodos_letivos')->onDelete('cascade');
                $table->boolean('bloqueada')->default(false);// Sinaliza bloqueio para novas reservas

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
        Schema::dropIfExists('restricoes');
    }
}
