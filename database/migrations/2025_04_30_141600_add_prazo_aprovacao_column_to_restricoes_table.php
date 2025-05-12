<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPrazoAprovacaoColumnToRestricoesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('restricoes', function (Blueprint $table) {
            $table->integer('prazo_aprovacao')->nullable();
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
            $table->dropColumn('prazo_aprovacao');
        });
    }
}
