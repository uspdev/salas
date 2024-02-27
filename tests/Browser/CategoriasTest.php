<?php

namespace Tests\Browser;

use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

use app\Models\User;
use Socialite;
use Auth;

use Mockery;
use Mockery\MockInterface;


class CategoriasTest extends DuskTestCase {

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
                    ->visit('/categorias/create')
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
                    ->visit('/categorias/create')
                    ->assertSee('403');
        });


        // Acesso desautenticado
        $this->browse(function (Browser $browser) {
            $browser->driver->manage()->deleteAllCookies();
            $browser->visit('/categorias/create')
                    ->assertSee('403');
        });
    }


    /**
     * Cria uma categoria valida.
     *
     * @return void
     * @depends testAcessoComoAdministrador
     */

     public function testCriaCategoriaValida () {

        // Login como administrador
        $this->browse(function (Browser $browser) {
            $this->loginUserAdm();
            $browser->loginAs(User::where('codpes', 1)->first());
        });
        
        // Cria uma finalidade
        $this->browse(function (Browser $browser) {
            $browser->visit('/categorias/create')
                    ->type('nome', 'categoria_teste')
                    ->press('Enviar')
                    ->assertPathIs('/categorias/*')
                    ->assertSee('categoria_teste');
        });
    }


    /**
     * Cria uma categoria invalida.
     *
     * @return void
     * @depends testCriaCategoriaValida
     */

    public function testCriaCategoriaInvalida () {

        // Login como administrador
        $this->browse(function (Browser $browser) {
            $this->loginUserAdm();
            $browser->loginAs(User::where('codpes', 1)->first());
        });

        // Cria uma finalidade nula
        $this->browse(function (Browser $browser) {
            $browser->visit('/categorias/create')
                    ->press('Enviar')
                    ->assertPathIs('/categorias/create')
                    ->assertSee('O título não pode ficar em branco');
        });

        // Cria uma finalidade duplicada
        $this->browse(function (Browser $browser) {
            $browser->visit('/categorias/create')
                    ->type('nome', 'categoria_teste')
                    ->press('Enviar')
                    ->assertPathIs('/categorias/create');
        });

    }

    
    /**
     * Edita uma categoria.
     *
     * @return void
     * @depends testCriaCategoriaValida
     */

    public function testEditaCategoria () {

        // Login como administrador
        $this->browse(function (Browser $browser) {
            $this->loginUserAdm();
            $browser->loginAs(User::where('codpes', 1)->first());
        });

        // Edita o nome da categoria
        $this->browse(function (Browser $browser) {
            $browser->visit('/categorias/1/edit')
                    ->type('nome', 'categoria_editada')
                    ->press('Enviar');
        });

        // Edita o vinculo de uma categoria
        $this->browse(function (Browser $browser) {
            $browser->visit('/categorias/1')
                    ->click('#usp')
                    ->press('Salvar');
        });

        // Edita o setor
        $this->browse(function (Browser $browser) {
            $browser->visit('/categorias/1')
                    ->click('.select2-search')
                    ->click('.select2-results > ul > li')
                    ->pause(1000)
                    ->press("@salvar_setores")
                    ->assertSee('atualizados com sucesso');
        });

        // Remove o setor
        $this->browse(function (Browser $browser) {
            $browser->visit('/categorias/1')
                    ->click('.select2-search')
                    ->click('.select2-results > ul > li')
                    ->pause(1000)
                    ->press("@salvar_setores")
                    ->assertSee('atualizados com sucesso');
        });

        // Adiciona uma pessoa
        $this->browse(function (Browser $browser) {
            $browser->visit('/categorias/1')
                    ->type('codpes', '7067011')
                    ->press('Enviar');
        });

        // Remove uma pessoa
        $this->browse(function (Browser $browser) {
            $browser->visit('/categorias/1')
                    ->waitFor('.fa-plus-square')->press('.fa-plus-square')
                    ->waitFor('#id_7067011')->press('#id_7067011 > form > button')
                    ->acceptDialog()
                    ->waitFor('.fa-plus-square')->press('.fa-plus-square');
        });
    }


    /**
     * Deleta uma categoria.
     *
     * @return void
     * @depends testCriaCategoriaValida
     */

    public function testDeletaCategoria() {

        // Login como administrador
        $this->browse(function (Browser $browser) {
            $this->loginUserAdm();
            $browser->loginAs(User::where('codpes', 1)->first());
        });

        // Deleta uma categoria que tem vinculo com alguma sala
        $this->browse(function (Browser $browser) {
            $browser->visit('/categorias/1')
                    ->press('deletar_categoria')
                    ->acceptDialog()
                    ->assertSee('Não é possível deletar essa categoria pois ela contém salas');
        });

        // Deleta uma categoria que nao tem vinculo com alguma sala
        $this->browse(function (Browser $browser) {
            $browser->visit('/categorias/6')
                    ->press('deletar_categoria')
                    ->acceptDialog()
                    ->assertSee('Categoria excluída com sucesso');
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