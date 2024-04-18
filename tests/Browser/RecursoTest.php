<?php

namespace Tests\Browser;

use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

use app\Models\User;
use Socialite;
use Auth;

use Mockery;
use Mockery\MockInterface;


class RecursoTest extends DuskTestCase {

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
                    ->visit('/recursos')
                    ->assertSee('403');
        });


        // Acesso desautenticado
        $this->browse(function (Browser $browser) {
            $browser->driver->manage()->deleteAllCookies();
            $browser->visit('/recursos')
                    ->assertSee('403');
        });


        // Acesso como administrador
        $this->loginUserAdm();
        $this->browse(function (Browser $browser) {
            $browser->loginAs(User::where('codpes', 1)->first())
                    ->visit('/recursos')
                    ->assertSee('Sistema Salas');
            
        });
    }


    /**
     * Create recurso
     *
     * @return void
     * @depends testAcessoARota
     */
    public function testCreateRecurso() {
        $this->loginUserAdm();

        // Cria um recurso nulo
        $this->browse(function (Browser $browser) {
            $browser->visit('/recursos/create')
                    ->press('Enviar')
                    ->assertPathIs('/recursos/create')
                    ->assertSee('O nome não pode ficar em branco');
        });

        // Cria um recurso
        $this->browse(function (Browser $browser) {
            $browser->visit('/recursos/create')
                    ->type('nome', 'recurso_teste')
                    ->press('Enviar')
                    ->assertPathIs('/recursos')
                    ->assertSee('recurso_teste');
        });

        // Cria um recurso duplicado
        $this->browse(function (Browser $browser) {
            $browser->visit('/recursos/create')
                    ->type('nome', 'recurso_teste')
                    ->press('Enviar')
                    ->assertPathIs('/recursos/create');
        });
    }


    /**
     * Edit recurso
     *
     * @return void
     * @depends testCreateRecurso
     */

     public function testEditRecurso() {

        // Edita um recurso que nao existe
        $this->browse(function (Browser $browser) {
            $browser->visit('/recursos/0/edit')
                    ->assertSee('404');
        });

        // Edita um recurso
        $this->browse(function (Browser $browser) {
            $browser->visit('/recursos/1/edit')
                    ->type('nome', 'recurso_editado')
                    ->press('Enviar')
                    ->assertPathIs('/recursos')
                    ->assertSee('recurso_editado');
        });

     }


    /**
     * Delete recurso
     *
     * @return void
     * @depends testEditRecurso
     */
    public function testDeleteRecurso() {
        $this->browse(function (Browser $browser) {
            $browser->visit('/recursos')
                    ->press('table > tbody > tr > td:nth-child(2) > form > button')
                    ->assertDialogOpened('Tem certeza?')->acceptDialog()
                    ->assertPathIs('/recursoss')
                    ->assertSee('Recurso excluído com sucesso.');

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
