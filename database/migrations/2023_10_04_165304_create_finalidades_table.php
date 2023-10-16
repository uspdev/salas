<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schema;

class CreateFinalidadesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('finalidades', function (Blueprint $table) {
            $table->id();
            $table->string('legenda');
            $table->string('cor');
            $table->timestamps();
        });

        // Popula instâncias padrão de finalidades no momento da migration.
        Artisan::call('db:seed', [
            '--class' => 'FinalidadeSeeder',
            '--force' => true // Para rodar o seeder em produção
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('finalidades');
    }
}
