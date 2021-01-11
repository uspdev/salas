<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Recurso;

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
    Recurso::factory(20)->create();

    }
}
