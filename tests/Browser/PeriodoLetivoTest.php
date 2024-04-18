<?php

namespace Tests\Browser;

use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

use app\Models\User;
use Socialite;
use Auth;

use Mockery;
use Mockery\MockInterface;


class PeriodoLetivoTest extends DuskTestCase {

    /**
     * Acesso a rota
     *
     * @return void
     */
    public function testAcessoARota() {

        // Acesso como usuario generico
        $this->loginUserGenerico();
        $this->browse(function (Browser $browser) {
            $browser->loginAs(User::where('codpes', 2)->first())
                    ->visit('/periodos_letivos/create')
                    ->assertSee('403');
        });


        // Acesso desautenticado
        $this->browse(function (Browser $browser) {
            $browser->driver->manage()->deleteAllCookies();
            $browser->visit('/periodos_letivos')
                    ->assertSee('Sistema Salas');
        });
        $this->browse(function (Browser $browser) {
            $browser->driver->manage()->deleteAllCookies();
            $browser->visit('/periodos_letivos/create')
                    ->assertSee('403');
        });


        // Acesso como administrador
        $this->loginUserAdm();
        $this->browse(function (Browser $browser) {
            $browser->loginAs(User::where('codpes', 1)->first())
                    ->visit('/periodos_letivos/create')
                    ->assertSee('Sistema Salas');
            
        });
    }


    /**
     * Create periodo letivo
     *
     * @return void
     * @depends testAcessoARota
     */
    public function testCreatePeriodoLetivo() {
        // Cria um periodo nulo
        $this->browse(function (Browser $browser) {
            $browser->visit('/periodos_letivos/create')
                    ->press('Enviar')
                    ->assertPathIs('/periodos_letivos/create');
        });

        // Cria um periodo valido
        $this->browse(function (Browser $browser) {
            $browser->visit('/periodos_letivos/create')
                    ->type('codigo', '20241')
                    ->type('data_inicio', '01/01/2024')
                    ->type('data_fim', '12/30/2024')
                    ->type('data_inicio_reservas', '02/01/2024')
                    ->type('data_fim_reservas', '11/01/2024')
                    ->press('Enviar')
                    ->assertPathIs('/periodos_letivos')
                    ->assertSee('20241');

        });

        // Cria um periodo invalido
        $this->browse(function (Browser $browser) {
            $browser->visit('/periodos_letivos/create')
                    ->type('codigo', '20241')
                    ->type('data_fim', '01/01/2024')
                    ->type('data_inicio', '12/30/2024')
                    ->type('data_inicio_reservas', '02/01/2024')
                    ->type('data_fim_reservas', '11/01/2024')
                    ->press('Enviar')
                    ->assertPathIs('/periodos_letivos/create')
                    ->assertSee('A data de término do período letivo deve ser após a data de início do período letivo.');

        });
        $this->browse(function (Browser $browser) {
            $browser->visit('/periodos_letivos/create')
                    ->type('codigo', '20241')
                    ->type('data_inicio', '01/01/2024')
                    ->type('data_fim', '12/30/2024')
                    ->type('data_fim_reservas', '02/01/2024')
                    ->type('data_inicio_reservas', '11/01/2024')
                    ->press('Enviar')
                    ->assertPathIs('/periodos_letivos/create')
                    ->assertSee('A data de término das reservas para o período letivo deve ser após a data de início das reservas para o período letivo.');

        });

        // Cria um periodo duplicado
        $this->browse(function (Browser $browser) {
            $browser->visit('/periodos_letivos/create')
                    ->type('codigo', '20241')
                    ->type('data_inicio', '01/01/2024')
                    ->type('data_fim', '12/30/2024')
                    ->type('data_inicio_reservas', '02/01/2024')
                    ->type('data_fim_reservas', '11/01/2024')
                    ->press('Enviar')
                    ->assertPathIs('/periodos_letivos/create');
        });

    }


    /**
     * Edit periodo letivo
     *
     * @return void
     * @depends testCreatePeriodoLetivo
     */
    public function testEditPeriodoLetivo() {
        // Edita um periodo
        $this->browse(function (Browser $browser) {
            $browser->visit('/periodos_letivos/1/edit')
                    ->type('codigo', '20231')
                    ->type('data_inicio', '01/01/2023')
                    ->type('data_fim', '12/30/2023')
                    ->type('data_inicio_reservas', '02/01/2023')
                    ->type('data_fim_reservas', '11/01/2023')
                    ->press('Enviar')
                    ->assertPathIs('/periodos_letivos')
                    ->assertSee('20231');

        });
    }

    
    /**
     * Delete periodo letivo
     *
     * @return void
     * @depends testEditPeriodoLetivo
     */

    public function testDeletePeriodoLetivo() {
        $this->browse(function (Browser $browser) {
            $browser->visit('/periodos_letivos')
                    ->click('.btn-danger')
                    ->acceptDialog()
                    ->visit('/periodos_letivos/create/1')
                    ->assertSee('404');

        });
    }


    /**
     * Mock login Socialite com a conta de administrador
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
}
