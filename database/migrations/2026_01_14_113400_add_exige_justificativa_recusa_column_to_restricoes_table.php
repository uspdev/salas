<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddExigeJustificativaRecusaColumnToRestricoesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('restricoes', function (Blueprint $table) {
            $table->boolean('exige_justificativa_recusa')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('restricoes', function (Blueprint $table) {
            $table->dropColumn('exige_justificativa_recusa');
        });
    }
}
