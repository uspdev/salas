<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Categoria;

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
    Categoria::factory(20)->create();
    }
}
