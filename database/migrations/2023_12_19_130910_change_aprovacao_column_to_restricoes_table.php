<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeAprovacaoColumnToRestricoesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('salas', function (Blueprint $table) {
            $table->dropColumn('aprovacao');
        });


        Schema::table('restricoes', function (Blueprint $table) {
            $table->tinyInteger('aprovacao')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('salas', function (Blueprint $table) {
            $table->tinyInteger('aprovacao')->default(0);
        });


        Schema::table('restricoes', function (Blueprint $table) {
            $table->dropColumn('aprovacao');
        });
    }
}
