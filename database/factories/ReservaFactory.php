<?php

namespace Database\Factories;

use App\Models\Reserva;
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
            'nome' => $this->faker->word(),
            'data' => $this->faker->date($format = 'd/m/Y'),
            'horario_inicio' => $this->faker->time($format = 'H:i'),
            'horario_fim' => $this->faker->time($format = 'H:i'),
            'cor' => $this->faker->hexcolor,
            'sala_id' => $this->faker->numberBetween($min = 1, $max = 5),
            'descricao' => $this->faker->sentence(4),
            'user_id' => 1,
        ];
    }
}
