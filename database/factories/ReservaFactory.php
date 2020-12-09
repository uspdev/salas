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
            'nome'        => $this->faker->sentence(2),
            'data_inicio' => $this->faker->dateTime(),
            'data_fim'    => $this->faker->dateTime(),
            'cor'         => $this->faker->hexcolor,
            'sala_id'     => Sala::factory()->create()->id,
        ];
    }
}
