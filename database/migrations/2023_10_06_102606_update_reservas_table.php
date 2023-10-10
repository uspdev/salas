<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateReservasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('reservas', function (Blueprint $table) {
            $table->foreignId('finalidade_id')->nullable()->constrained();
            $table->dropColumn('cor');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('reservas', function (Blueprint $table) {
            $table->dropConstrainedForeignId('finalidade_id');
            $table->string('cor')->default('#408AD2'); // Define um cor arbitrária para as reservas aprovadas no caso de reverter a migration.
        });
    }
}
