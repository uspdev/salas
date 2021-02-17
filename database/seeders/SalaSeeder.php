<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Sala;

class SalaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $sala = [
            'nome'         => 'Sala 039',
            'categoria_id' => 1,
            'capacidade'   => 10,

        ];
        
    Sala::create($sala);
    Sala::factory(20)->create();
    }
}
