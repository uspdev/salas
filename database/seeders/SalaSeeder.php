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
            'nome' => 'Sala 101',
        ];
        
    Sala::create($sala);
    Sala::factory(20)->create();
    }
}
