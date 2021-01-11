<?php

namespace Database\Factories;

use App\Models\Reserva;
use App\Models\Sala;
use Illuminate\Database\Eloquent\Factories\Factory;

class ReservaFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Reserva::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {   
        return [
            'nome'           => $this->faker->sentence(2),
            'data_inicio'    => '20/12/2020',
            'data_fim'       => '20/12/2020',
            'horario_inicio' => $this->faker->time(),
            'horario_fim'    => $this->faker->time(),
            'full_day_event' => 0,
            'cor'            => $this->faker->hexcolor,
            'sala_id'        => Sala::factory()->create()->id,
            'descricao'      => $this->faker->sentence(4),
        ];
    }
}
