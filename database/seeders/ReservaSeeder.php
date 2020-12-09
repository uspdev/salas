<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Reserva;

class ReservaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $reserva = [
            'nome' => 'Aula FLP32020',
            'data_inicio' => '2020-03-09 00:00:00',
            'data_fim' => '2020-05-01 01:00:00',
            'cor' => '#aea1ff',           
        ];
        
    Reserva::create($reserva);
    Reserva::factory(20)->create();
    }
}
