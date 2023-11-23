<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddMotivoBloqueioToRestricoesTable extends Migration
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
                $table->string('motivo_bloqueio')->nullable(); // Motivo do bloqueio da sala.
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
        if (Schema::hasColumn('restricoes', 'motivo_bloqueio')) {
            Schema::table('restricoes', function (Blueprint $table) {
                $table->dropColumn('motivo_bloqueio');
            });
        }
    }
}
