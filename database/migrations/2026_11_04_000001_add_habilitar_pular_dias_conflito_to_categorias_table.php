<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class AddHabilitarPularDiasConflitoToCategoriasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('categorias', function (Blueprint $table) {
            $table->boolean('habilitar_pular_dias_conflito')->nullable()->default(true)->after('vinculos');
        });

        DB::table('categorias')
            ->whereNull('habilitar_pular_dias_conflito')
            ->update(['habilitar_pular_dias_conflito' => true]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('categorias', function (Blueprint $table) {
            $table->dropColumn('habilitar_pular_dias_conflito');
        });
    }
}
