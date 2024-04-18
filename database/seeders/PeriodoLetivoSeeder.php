<?php

namespace Database\Seeders;

use App\Models\PeriodoLetivo;
use Illuminate\Database\Seeder;

class PeriodoLetivoSeeder extends Seeder {
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $periodo = [
            'codigo' => '2024',
            'data_inicio' => '2024/01/01',
            'data_fim' => '2024/12/30',
            'data_inicio_reservas' => '2024/02/01',
            'data_fim_reservas' => '2024/11/01'
        ];

        PeriodoLetivo::create($periodo);
    }
}
