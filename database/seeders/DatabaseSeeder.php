<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            PeriodoLetivoSeeder::class,
            UserSeeder::class,
            CategoriaSeeder::class,
            RecursoSeeder::class,
            SalaSeeder::class,
            ReservaSeeder::class
        ]);

    }
}
