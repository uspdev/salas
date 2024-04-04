<?php

namespace Tests\Browser;

use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

use app\Models\User;
use Socialite;
use Auth;

use Mockery;
use Mockery\MockInterface;

class ReservasTest extends DuskTestCase {
    /**
     * Elimina os cookies ao inicio de cada teste.
     *
     * @return void
     */

     protected function setUp() : void {
        parent::setUp();
        foreach (static::$browsers as $browser) {
            $browser->driver->manage()->deleteAllCookies();
        }
    }

    /**
     * Acesso a rota como administrador.
     *
     * @return void
     */

     public function testAcessoComoAdministrador () {
        $this->loginUserAdm();
        $this->browse(function (Browser $browser) {
            $browser->loginAs(User::where('codpes', 1)->first())
                    ->visit('/reservas/my')
                    ->assertSee('Minhas Reservas');
            
            $browser->visit('/reservas/1/edit')
                    ->assertSee('Sistema Salas');
            
            $browser->visit('/reservas/create')
                    ->assertSee('Sistema Salas');
        });
    }


    /**
     * Acesso a rota como nao administrador.
     *
     * @return void
     */

     public function testAcessoComoNaoAdministrador () {

        // Acesso desautenticado
        $this->browse(function (Browser $browser) {
            $browser->visit('/reservas')
                    ->assertSee('MethodNotAllowedHttpException');
        });
        $this->browse(function (Browser $browser) {
            $browser->visit('/reservas/my')
                    ->assertSee('403');
        });
        $this->browse(function (Browser $browser) {
            $browser->visit('/reservas/1')
                    ->assertSee('Indra Seixas Neiva - 7067011');
        });

    }


    /**
     * Aprovar reserva sem privilegio
     *
     * @return void
     * @depends testAcessoComoAdministrador
     */

     public function testAprovarReservaSemPrivilegio () {

        // Aprova com conta generica
        $this->browse(function (Browser $browser) {
            $this->loginUserGenerico();
            $browser->visit('/reservas/1/aprovar')
                    ->assertSee('403');
        });

        // Aprova com conta do dono da reserva sem privilegio
        $this->browse(function (Browser $browser) {
            $browser->driver->manage()->deleteAllCookies();
            $this->loginUserDonoReserva();
            $browser->visit('/reservas/1/aprovar')
                    ->assertSee('403');
        });
    }


    /**
     * Aprovar reserva com privilegio
     *
     * @return void
     * @depends testAcessoComoAdministrador
     */

     public function testAprovarReservaComPrivilegio () {

        // Login como administrador
        $this->browse(function (Browser $browser) {
            $this->loginUserAdm();
            $browser->loginAs(User::where('codpes', 1)->first())
                    ->visit('/reservas/my')
                    ->assertSee('Minhas Reservas');
        });

        // Aprova com conta do dono da reserva sem privilegio
        $this->browse(function (Browser $browser) {
            $browser->visit('/reservas/1/aprovar')
                    ->assertSee('403');
        });
    }


    /**
     * Cria uma reserva
     *
     * @return void
     * @depends testAcessoComoAdministrador
     */

     public function testCriaReservaValida () {
        
        // Login como administrador
        $this->browse(function (Browser $browser) {
            $this->loginUserAdm();
            $browser->loginAs(User::where('codpes', 1)->first())
                    ->visit('/reservas/my')
                    ->assertSee('Minhas Reservas');
        });

        // Cria uma reserva valida
        $this->browse(function (Browser $browser) {
            $browser->visit('/reservas/create')
                    ->type('nome', 'reserva_teste')
                    ->type('data', '14/01/2021')
                    ->type('horario_inicio', '8:00')
                    ->type('horario_fim', '9:00')
                    ->select('sala_id', 1)
                    ->type('descricao', 'reserva_teste')
                    ->press('Enviar')
                    ->assertPathIs('/reservas')
                    ->visit('/reservas/2')
                    ->assertSee('reserva_teste');
        });
     }
    
    

    /**
     * Cria uma reserva
     *
     * @return void
     * @depends testCriaReservaValida
     */
    public function testCriaReservaInvalida () {

        // Login como administrador
        $this->browse(function (Browser $browser) {
            $this->loginUserAdm();
            $browser->loginAs(User::where('codpes', 1)->first())
                    ->visit('/reservas/my')
                    ->assertSee('Minhas Reservas');
        });

        // Cria uma reserva invalida
        $this->browse(function (Browser $browser) {
            $browser->visit('/reservas/create')
                    ->type('nome', 'reserva_teste')
                    ->type('data', '14/01/2021')
                    ->type('horario_inicio', '12:00')
                    ->type('horario_fim', '13:00')
                    ->select('sala_id', 1)
                    ->type('descricao', 'Teste de reserva')
                    ->press('Enviar')
                    ->assertPathIs('/reservas/create');
        });
        $this->browse(function (Browser $browser) {
            $browser->visit('/reservas/create')
                    ->type('nome', 'reserva_teste')
                    ->type('data', '14/01/2021')
                    ->type('horario_inicio', '12:30')
                    ->type('horario_fim', '12:40')
                    ->select('sala_id', 1)
                    ->type('descricao', 'Teste de reserva')
                    ->press('Enviar')
                    ->assertPathIs('/reservas/create');
        });
        $this->browse(function (Browser $browser) {
            $browser->visit('/reservas/create')
                    ->type('nome', 'reserva_teste')
                    ->type('data', '14/01/2021')
                    ->type('horario_inicio', '11:00')
                    ->type('horario_fim', '14:00')
                    ->select('sala_id', 1)
                    ->type('descricao', 'Teste de reserva')
                    ->press('Enviar')
                    ->assertPathIs('/reservas/create');
        });
        $this->browse(function (Browser $browser) {
            $browser->visit('/reservas/create')
                    ->type('nome', 'reserva_teste')
                    ->type('data', '14/01/2021')
                    ->type('horario_inicio', '12:00')
                    ->type('horario_fim', '12:20')
                    ->select('sala_id', 1)
                    ->type('descricao', 'Teste de reserva')
                    ->press('Enviar')
                    ->assertPathIs('/reservas/create');
        });
        $this->browse(function (Browser $browser) {
            $browser->visit('/reservas/create')
                    ->type('nome', 'reserva_teste')
                    ->type('data', '14/01/2021')
                    ->type('horario_inicio', '12:30')
                    ->type('horario_fim', '14:00')
                    ->select('sala_id', 1)
                    ->type('descricao', 'Teste de reserva')
                    ->press('Enviar')
                    ->assertPathIs('/reservas/create');
        });
    }

    /**
     * Edita uma reserva
     *
     * @return void
     * @depends testCriaReservaValida
     */

     public function testEditaReserva () {
        
        // Login como administrador
        $this->browse(function (Browser $browser) {
            $this->loginUserAdm();
            $browser->loginAs(User::where('codpes', 1)->first())
                    ->visit('/reservas/my')
                    ->assertSee('Minhas Reservas');
        });

        // Edita uma reserva
        $this->browse(function (Browser $browser) {
            $browser->visit('/reservas/1/edit')
                    ->type('nome', 'reserva_teste_editada')
                    ->type('data', '14/02/2021')
                    ->type('horario_inicio', '9:00')
                    ->type('horario_fim', '10:00')
                    ->type('descricao', 'reserva_editada')
                    ->press('Enviar')
                    ->assertPathIs('/reservas')
                    ->visit('/reservas/1')
                    ->assertSee('reserva_editada')
                    ->assertSee('14/02/2021')
                    ->assertSee('9:00')
                    ->assertSee('10:00');
        });
     }


    /**
     * Exclui uma reserva
     *
     * @return void
     * @depends testCriaReservaValida
     */

     public function testExcluiReserva () {
        
        // Login como administrador
        $this->browse(function (Browser $browser) {
            $this->loginUserAdm();
            $browser->loginAs(User::where('codpes', 1)->first())
                    ->visit('/reservas/my')
                    ->assertSee('Minhas Reservas');
        });

        // Exclui uma reserva
        $this->browse(function (Browser $browser) {
            $browser->visit('/reservas/1')
                    ->press('Excluir')
                    ->acceptDialog()
                    ->assertPathIs('/reservas');

        });
     }


    /**
     * Mock login Socialite com a conta de administrador.
     *
     * @return void
     */
    
     public function loginUserAdm() {
        $user = $this->mock('Laravel\Socialite\Contracts\User', function(MockInterface $mock) {
            $mock->codpes = 1;
            $mock->nompes = 'Conta ADM';
            $mock->email = 'admin@salas.teste';
            $mock->emailUsp = 'admin@salas.teste';
            $mock->emailAlternativo = 'admin@salas.teste';
            $mock->telefone = 11999999999;
            $mock->vinculo = array();
        });

        $provider = $this->mock('Laravel\Socialite\Contracts\Provider', function(MockInterface $mock) use ($user) {
            $mock
                ->shouldReceive('user')
                ->andReturn($user);
        });

        Socialite::shouldReceive('driver')->once()->with('senhaunica')->andReturn($provider);

        $response = $this->get('/callback');
        $response->assertStatus(302);
        $this->assertAuthenticated();
    }


    /**
     * Mock login Socialite com a conta generica
     *
     * @return void
     */
     public function loginUserGenerico() {
        $user = $this->mock('Laravel\Socialite\Contracts\User', function(MockInterface $mock) {
            $mock->codpes = 2;
            $mock->nompes = 'Conta Generica';
            $mock->email = 'generico@salas.teste';
            $mock->emailUsp = 'generico@salas.teste';
            $mock->emailAlternativo = 'generico@salas.teste';
            $mock->telefone = 11999999999;
            $mock->vinculo = array();
        });

        $provider = $this->mock('Laravel\Socialite\Contracts\Provider', function(MockInterface $mock) use ($user) {
            $mock
                ->shouldReceive('user')
                ->andReturn($user);
        });

        Socialite::shouldReceive('driver')->once()->with('senhaunica')->andReturn($provider);

        $response = $this->get('/callback');
    }


    /**
     * Mock login Socialite com o dono da reserva
     *
     * @return void
     */
     public function loginUserDonoReserva() {
        $user = $this->mock('Laravel\Socialite\Contracts\User', function(MockInterface $mock) {
            $mock->codpes = 7067011;
            $mock->nompes = 'Indra Seixas Neiva';
            $mock->email = 'indra@salas.teste';
            $mock->emailUsp = 'indra@salas.teste';
            $mock->emailAlternativo = 'indra@salas.teste';
            $mock->telefone = 11999999999;
            $mock->vinculo = array();
        });

        $provider = $this->mock('Laravel\Socialite\Contracts\Provider', function(MockInterface $mock) use ($user) {
            $mock
                ->shouldReceive('user')
                ->andReturn($user);
        });

        Socialite::shouldReceive('driver')->once()->with('senhaunica')->andReturn($provider);

        $response = $this->get('/callback');
    }
}