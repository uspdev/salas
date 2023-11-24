<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDuracaoMaximaToRestricoesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable('restricoes')) {
            Schema::table('restricoes', function (Blueprint $table) {
                $table->integer('duracao_maxima')->nullable(); // Duração máxima para a reserva, em minutos.
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
        if (Schema::hasColumn('restricoes', 'duracao_maxima')) {
            Schema::table('restricoes', function (Blueprint $table) {
                $table->dropColumn('duracao_maxima');
            });
        }
    }
}
