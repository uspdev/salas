<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class FinalidadeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('finalidades')->insert([
            'legenda' => 'Graduação',
            'cor' => '#FFFCB7',
            'created_at' => now(),
            'updated_at' => now()
        ]);

        DB::table('finalidades')->insert([
            'legenda' => 'Pós-Graduação',
            'cor' => '#C7F3BA',
            'created_at' => now(),
            'updated_at' => now()
        ]);

        DB::table('finalidades')->insert([
            'legenda' => 'Especialização',
            'cor' => '#BAE3F3',
            'created_at' => now(),
            'updated_at' => now()
        ]);

        DB::table('finalidades')->insert([
            'legenda' => 'Extensão',
            'cor' => '#EFDFCF',
            'created_at' => now(),
            'updated_at' => now()
        ]);

        DB::table('finalidades')->insert([
            'legenda' => 'Defesa',
            'cor' => '#EEC9F1',
            'created_at' => now(),
            'updated_at' => now()
        ]);

        DB::table('finalidades')->insert([
            'legenda' => 'Qualificação',
            'cor' => '#CCCC99',
            'created_at' => now(),
            'updated_at' => now()
        ]);

        DB::table('finalidades')->insert([
            'legenda' => 'Reunião',
            'cor' => '#F78B83',
            'created_at' => now(),
            'updated_at' => now()
        ]);

        DB::table('finalidades')->insert([
            'legenda' => 'Evento',
            'cor' => '#FBCCAC',
            'created_at' => now(),
            'updated_at' => now()
        ]);
    }
}
