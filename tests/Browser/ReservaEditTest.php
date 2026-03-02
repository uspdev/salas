<?php

namespace Tests\Browser;

use App\Models\Reserva;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use PHPUnit\Framework\Attributes\Group;
use Tests\DuskTestCase;

class ReservaEditTest extends DuskTestCase
{
    /**
     * A Dusk test example.
     */
    #[Group('categorias')]
    public function testCriarPrimeiraReserva(): void
    {
        $user = User::where('id',1023)->first();
        $this->browse(function (Browser $browser) use ($user){
            $browser->loginAs($user)
            ->visit('/reservas/create')
            ->typeSlowly('nome','Reunião STI',1)
            ->typeSlowly('data','01/01/2026',1)
            ->clickAtXPath('//body')
            ->typeSlowly('horario_inicio','8:00',1)
            ->typeSlowly('horario_fim','10:00',1)
            ->radio('rep_bool','Sim')
            ->check('repeat_days[1]')
            ->check('repeat_days[2]')
            ->check('repeat_days[3]')
            ->check('repeat_days[4]')
            ->check('repeat_days[5]')
            ->typeSlowly('repeat_until','31/01/2026',1)
            ->clickAtXPath('//body')
            ->pause(300)
            ->click('button[type="submit"]');
        });
    }

    public function testCriarSegundaReserva(): void
    {
        $user = User::where('id',1023)->first();
        $this->browse(function (Browser $browser) use ($user){
            $browser->loginAs($user)
            ->visit('/reservas/create')
            ->typeSlowly('nome','Aula de Alemão',1)
            ->typeSlowly('data','01/02/2026',1)
            ->clickAtXPath('//body')
            ->typeSlowly('horario_inicio','8:00',1)
            ->typeSlowly('horario_fim','10:00',1)
            ->radio('rep_bool','Sim')
            ->check('repeat_days[1]')
            ->check('repeat_days[2]')
            ->check('repeat_days[3]')
            ->check('repeat_days[4]')
            ->check('repeat_days[5]')
            ->typeSlowly('repeat_until','28/02/2026',1)
            ->clickAtXPath('//body')
            ->click('button[type="submit"]');
        });
    }

    //criar reserva pulando conflito
    public function testEditarSegundaReserva(): void
    {
        $user = User::where('id',1023)->first();
        $this->browse(function (Browser $browser) use ($user){
            $browser->loginAs($user)
            ->pause(1000)
            ->click('a[title="Editar"]')
            ->typeSlowly('nome','Aula de Alemão IV',10)
            ->typeSlowly('data','01/01/2026',10) // data conflituosa com a primeira reserva!
            ->pause(2000)
            ->clickAtXPath('//body')
            ->typeSlowly('horario_inicio','8:00',10)
            ->typeSlowly('horario_fim','10:00',10)
            ->radio('rep_bool','Sim')
            ->check('repeat_days[1]')
            ->check('repeat_days[2]')
            ->check('repeat_days[3]')
            ->check('repeat_days[4]')
            ->check('repeat_days[5]')
            ->typeSlowly('repeat_until','28/02/2026',10)
            ->clickAtXPath('//body')
            ->click('button[type="submit"]') //erro
            ->pause(2000)
            ->check('skip') // pular as datas com conflito...
            ->pause(1000)
            ->click('button[type="submit"]')
            ->pause(2000);
        });
    }

}
