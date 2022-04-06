<?php

namespace Database\Seeders;

use App\Models\Categoria;
use Illuminate\Database\Seeder;

class CategoriaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $categoria = [
            'nome' => 'Filosofia',
        ];

        Categoria::create($categoria);
        Categoria::factory(4)->create();
    }
}
