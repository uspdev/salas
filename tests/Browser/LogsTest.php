<?php

namespace Tests\Browser;

use Laravel\Dusk\Browser;
use Tests\DuskTestCase;


class LogsTest extends DuskTestCase {

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
     * Acesso a rota como nao administrador.
     *
     * @return void
     */

     public function testAcessoComoNaoAdministrador () {

        // Acesso desautenticado
        $this->browse(function (Browser $browser) {
           $browser->visit('/logs')
                   ->assertSee('403');
       });

       // Acesso como usuario generico
       $this->loginUserGenerico();
       $this->browse(function (Browser $browser) {
           $browser->loginAs(User::where('codpes', 2)->first())
                   ->visit('/logs')
                   ->assertSee('403');
       });
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
                    ->visit('/logs')
                    ->assertSee('Laravel Log Viewer');
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