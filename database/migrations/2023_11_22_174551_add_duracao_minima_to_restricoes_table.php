<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDuracaoMinimaToRestricoesTable extends Migration
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
                $table->integer('duracao_minima')->nullable(); // Duração mínima para a reserva, em minutos.
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
        if (Schema::hasColumn('restricoes', 'duracao_minima')) {
            Schema::table('restricoes', function (Blueprint $table) {
                $table->dropColumn('duracao_minima');
            });
        }
    }
}
