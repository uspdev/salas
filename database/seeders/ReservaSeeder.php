<?php

namespace Database\Seeders;

use App\Models\Reserva;
use Illuminate\Database\Seeder;

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
            'data' => '14/01/2021',
            'horario_inicio' => '12:00',
            'horario_fim' => '13:00',
            'finalidade_id' => 1,
            'sala_id' => 1,
            'descricao' => 'Aula de Política III do ano de 2020.',
            'user_id' => 1,
        ];

        Reserva::create($reserva);
        Reserva::factory(50000)->create();
    }
}
