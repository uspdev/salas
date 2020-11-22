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
        ];
        
    Reserva::create($reserva);
    Reserva::factory(20)->create();
    }
}
