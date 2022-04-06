<?php

namespace Database\Seeders;

use App\Models\Recurso;
use Illuminate\Database\Seeder;

class RecursoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $recurso = [
            'nome' => 'Projetor',
        ];

        Recurso::create($recurso);
        Recurso::factory(5)->create();
    }
}
