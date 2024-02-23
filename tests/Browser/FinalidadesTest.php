<?php

namespace Tests\Browser;

use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

use app\Models\User;
use Socialite;
use Auth;

use Mockery;
use Mockery\MockInterface;

class FinalidadesTest extends DuskTestCase {

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
        $this->browse(function (Browser $browser) {
            $this->loginUserAdm();
            $browser->loginAs(User::where('codpes', 1)->first())
                    ->visit('/finalidades/create')
                    ->assertSee('Sistema Salas');
        });
    }


    /**
     * Acesso a rota como nao administrador.
     *
     * @return void
     */

     public function testAcessoComoNaoAdministrador () {

        // Acesso como usuario generico
        $this->loginUserGenerico();
        $this->browse(function (Browser $browser) {
            $browser->loginAs(User::where('codpes', 2)->first())
                    ->visit('/finalidades/create')
                    ->assertSee('403');
        });


        // Acesso desautenticado
        $this->browse(function (Browser $browser) {
            $browser->driver->manage()->deleteAllCookies();
            $browser->visit('/finalidades/create')
                    ->assertSee('403');
        });
    }


    /**
     * Cria uma finalidade valida.
     *
     * @return void
     * @depends testAcessoComoAdministrador
     */

    public function testCriaFinalidadeValida () {

        // Login como administrador
        $this->browse(function (Browser $browser) {
            $this->loginUserAdm();
            $browser->loginAs(User::where('codpes', 1)->first());
        });
        
        // Cria uma finalidade
        $this->browse(function (Browser $browser) {
            $browser->visit('/finalidades/create')
                    ->type('legenda', 'finalidade_teste')
                    ->type('cor', '#dc3545')
                    ->press('Enviar')
                    ->assertPathIs('/finalidades')
                    ->assertSee('finalidade_teste');
        });
    }


    /**
     * Cria uma finalidade invalida.
     *
     * @return void
     * @depends testCriaFinalidadeValida
     */

    public function testCriaFinalidadeInvalida () {

        // Login como administrador
        $this->browse(function (Browser $browser) {
            $this->loginUserAdm();
            $browser->loginAs(User::where('codpes', 1)->first());
        });

        // Cria uma finalidade nula
        $this->browse(function (Browser $browser) {
            $browser->visit('/finalidades/create')
                    ->press('Enviar')
                    ->assertPathIs('/finalidades/create');
        });

        // Cria uma finalidade duplicada
        $this->browse(function (Browser $browser) {
            $browser->visit('/finalidades/create')
                    ->type('legenda', 'finalidade_teste')
                    ->type('cor', '#dc3545')
                    ->press('Enviar')
                    ->assertPathIs('/finalidades/create');
        });
    }


    /**
     * Edita uma finalidade.
     *
     * @return void
     * @depends testCriaFinalidadeValida
     */

    public function testEditaFinalidade () {

        // Login como administrador
        $this->browse(function (Browser $browser) {
            $this->loginUserAdm();
            $browser->loginAs(User::where('codpes', 1)->first());
        });

        // Edita uma finalidade
        $this->browse(function (Browser $browser) {
            $browser->visit('/finalidades/1/edit')
                    ->type('legenda', 'finalidade_editada')
                    ->press('Enviar')
                    ->assertPathIs('/finalidades')
                    ->assertSee('Finalidade atualizada com sucesso');
        });
    }


    /**
     * Exclui uma finalidade.
     *
     * @return void
     * @depends testCriaFinalidadeValida
     */

    public function testExcluiFinalidade () {

        // Login como administrador
        $this->browse(function (Browser $browser) {
            $this->loginUserAdm();
            $browser->loginAs(User::where('codpes', 1)->first());
        });

        // Exclui uma finalidade
        $this->browse(function (Browser $browser) {
            $browser->visit('/finalidades')
                    ->press('.list-group-item:nth-child(1) form > .btn')
                    ->waitForText('Excluir a finalidade')
                    ->press('Excluir')
                    ->assertPathIs('/finalidades')
                    ->assertSee('Finalidade excluída com sucesso');
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
     * Mock login Socialite com a conta generica.
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