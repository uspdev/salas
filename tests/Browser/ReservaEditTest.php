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
            ->pause(2000)
            ->typeSlowly('nome','Reunião STI',100)
            ->typeSlowly('data','01/01/2026',100)
            ->clickAtXPath('//body')
            ->typeSlowly('horario_inicio','8:00',100)
            ->typeSlowly('horario_fim','10:00',100)
            ->radio('rep_bool','Sim')
            ->check('repeat_days[1]')
            ->check('repeat_days[2]')
            ->check('repeat_days[3]')
            ->check('repeat_days[4]')
            ->check('repeat_days[5]')
            ->typeSlowly('repeat_until','31/01/2026',100)
            ->clickAtXPath('//body')
            ->pause(400)
            ->click('button[type="submit"]')
            ->pause(2000)
            ->assertSee('Reserva(s) realizada(s) com sucesso.')
            ->pause(2000);
        });
    }

    public function testCriarSegundaReserva(): void
    {
        $user = User::where('id',1023)->first();
        $this->browse(function (Browser $browser) use ($user){
            $browser->loginAs($user)
            ->pause(2000)
            ->visit('/reservas/create')
            ->typeSlowly('nome','Aula de Alemão',100)
            ->typeSlowly('data','01/02/2026',100)
            ->clickAtXPath('//body')
            ->typeSlowly('horario_inicio','8:00',50)
            ->typeSlowly('horario_fim','10:00',50)
            ->radio('rep_bool','Sim')
            ->check('repeat_days[1]')
            ->check('repeat_days[2]')
            ->check('repeat_days[3]')
            ->check('repeat_days[4]')
            ->check('repeat_days[5]')
            ->typeSlowly('repeat_until','28/02/2026',100)
            ->clickAtXPath('//body')
            ->pause(400)
            ->click('button[type="submit"]')
            ->pause(2000);
        });
    }

    //criar reserva pulando conflito
    public function testEditarSegundaReserva(): void
    {
        $user = User::where('id',1023)->first();
        $this->browse(function (Browser $browser) use ($user){
            $browser->loginAs($user)
            ->click('a[title="Editar"]')
            ->assertSee('Atenção: Você está editando um grupo de reservas simultaneamente!')
            ->typeSlowly('nome','Aula de Alemão IV',100)
            ->typeSlowly('data','01/01/2026',50) // data conflituosa com a primeira reserva!
            ->pause(2000)
            ->clickAtXPath('//body')
            ->typeSlowly('horario_inicio','8:00',100)
            ->typeSlowly('horario_fim','10:00',100)
            ->radio('rep_bool','Sim')
            ->check('repeat_days[1]')
            ->check('repeat_days[2]')
            ->check('repeat_days[3]')
            ->check('repeat_days[4]')
            ->check('repeat_days[5]')
            ->pause(400)
            ->typeSlowly('repeat_until','28/02/2026',100)
            ->clickAtXPath('//body')
            ->click('button[type="submit"]') //erro
            ->pause(2000)
            ->check('skip') // pular as datas com conflito...
            ->pause(4000)
            ->click('button[type="submit"]')
            ->pause(2000);
        });
    }

}
