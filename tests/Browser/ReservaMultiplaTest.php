<?php

namespace Tests\Browser;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;
use App\Helpers\UspdevDuskTrait;
use Carbon\Carbon;

class ReservaMultiplaTest extends DuskTestCase
{
    use UspdevDuskTrait;
    /**
     * A Dusk test example.
     */
    protected function setUp(): void
    {
        parent::setUp();
        $this->setupAdminAndUser(); // cria usuários $this->commonUser e $this->adminUser
    }
    public function testReservaMultipla(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/loginlocal')
                    ->type('email', $this->adminUser->email)
                    ->type('password', 'password')
                    ->press('Entrar')
                    ->pause(1000)
                    ->clickLink('Administração')
                    ->pause(1250)
                    ->clickLink('Cadastrar Categoria')
                    ->pause(1250)
                    ->typeSlowly('nome','Prédio da Letras', 100)
                    ->pause(2000)
                    ->press('Enviar')
                    ->pause(2000);

            $categoria_id = \App\Models\Categoria::select('id')->latest()->first();

            $browser->clickLink('Administração')
                    ->pause(1850)
                    ->clickLink('Cadastrar Sala')
                    ->pause(1250)
                    ->typeSlowly('nome','Sala 171', 100)
                    ->typeSlowly('capacidade','123', 150)
                    ->select('categoria_id', $categoria_id->id) //Selecionando o ID da cat. criada
                    ->pause(3300)
                    ->press('Enviar')
                    ->pause(2300);

            $sala_id = \App\Models\Sala::select('id')->latest()->first();
            
            $primeiro_dia_do_semestre = Carbon::create(now()->format('Y'), 3)->firstOfMonth(Carbon::MONDAY);
            $ultimo_dia_do_semestre = Carbon::create(now()->format('Y'), 6)->lastOfMonth(Carbon::FRIDAY);
            
                    //3. Por fim, cria-se uma reserva inserindo a sala que desejamos.
                    $browser->clickLink('Nova reserva')
                    ->typeSlowly('nome','Introdução aos estudos Clássicos I')
                    ->type('data',$primeiro_dia_do_semestre->format('d/m/Y'))
                    ->typeSlowly('horario_inicio','8:00', 50)
                    ->typeSlowly('horario_fim','10:00', 50)
                    ->pause(1000)
                    ->select('sala_id',$sala_id->id)
                    ->clickAtXPath('//body') //clica fora do "calendário"
                    ->radio('rep_bool','Sim')
                    ->check('repeat_days[1]')
                    ->check('repeat_days[3]')
                    ->typeSlowly('repeat_until', $ultimo_dia_do_semestre->format('d/m/Y'))
                    ->pause(6000)
                    ->press('Enviar')
                    ->pause(6000);
        });
    }
}
