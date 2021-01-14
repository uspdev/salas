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
            'nome'           => 'Aula FLP32020',
            'data'           => '14/01/2021',
            'horario_inicio' => '12:00',
            'horario_fim'    => '13:00',
            'cor'            => '#aea1ff',
            'sala_id'        => 1,
            'descricao'      => 'Aula de Política III do ano de 2020.'
        ];
        
    Reserva::create($reserva);
    Reserva::factory(20)->create();
    }
}
