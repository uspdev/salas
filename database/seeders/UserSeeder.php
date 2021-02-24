<?php

namespace Database\Seeders;
use Uspdev\Replicado\Pessoa;
use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = [
            'codpes' => 7067011,
            'name' => Pessoa::dump(7067011)['nompes'],
            'email' => Pessoa::emailusp(7067011),
        ];
        User::create($user);
    }
}
