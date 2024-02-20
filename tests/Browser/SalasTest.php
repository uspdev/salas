<?php

namespace Tests\Browser;

use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

use app\Models\User;
use Socialite;
use Auth;

use Mockery;
use Mockery\MockInterface;


class SalasTest extends DuskTestCase {

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
                    ->visit('/salas/create')
                    ->assertSee('403');
        });


        // Acesso desautenticado
        $this->browse(function (Browser $browser) {
            $browser->driver->manage()->deleteAllCookies();
            $browser->visit('/salas')
                    ->assertSee('Sistema Salas');
        });


        // Acesso como administrador
        $this->loginUserAdm();
        $this->browse(function (Browser $browser) {
            $browser->loginAs(User::where('codpes', 1)->first())
                    ->visit('/salas/create')
                    ->assertSee('Sistema Salas');
            
        });
    }


    /**
     * Create salas
     *
     * @return void
     * @depends testAcessoARota
     */
    public function testCreateSalas() {
        $this->loginUserAdm();

        // Cria uma sala com campos nao preenchidos
        $this->browse(function (Browser $browser) {
            $browser->visit('/salas/create')
                    ->press('Enviar')
                    ->assertPathIs('/salas/create')
                    ->assertSee('O nome não pode ficar em branco')
                    ->assertSee('A categoria não pode ficar em branco.')
                    ->assertSee('A capacidade não pode ficar em branco.');
        });

        // Cria uma sala
        $this->browse(function (Browser $browser) {
            $browser->visit('/salas/create')
                    ->type('nome', 'sala_teste')
                    ->type('capacidade', '10')
                    ->select('categoria_id', 1)
                    ->click('@recurso_1')
                    ->press('Enviar')
                    ->assertPathIs('/salas/*/')
                    ->assertSee('sala_teste');
        });

        // Cria uma sala duplicada
        $this->browse(function (Browser $browser) {
            $browser->visit('/salas/create')
                    ->type('nome', 'sala_teste')
                    ->type('capacidade', '10')
                    ->select('categoria_id', 1)
                    ->click('@recurso_1')
                    ->press('Enviar')
                    ->assertPathIs('/salas/create');
        });
    }


    /**
     * Edit salas
     *
     * @return void
     * 
     */

     public function testEditSalas() {

        // Edita uma sala que nao existe
        $this->browse(function (Browser $browser) {
            $browser->visit('/salas/0/edit')
                    ->assertSee('404');
        });

        // Remove os atributos da sala
        $this->browse(function (Browser $browser) {
            $browser->visit('/salas/1/edit')
                    ->type('nome', '')
                    ->type('capacidade', '')
                    ->select('categoria_id', 0)
                    ->press('Enviar')
                    ->assertSee('O nome não pode ficar em branco.')
                    ->assertSee('A capacidade não pode ficar em branco.');
                
        });

        // Edita a sala com atributos validos
        $this->browse(function (Browser $browser) {
            $browser->visit('/salas/1/edit')
                    ->type('nome', 'sala_editada')
                    ->type('capacidade', '9999')
                    ->select('categoria_id', 2)
                    ->click('@recurso_1')
                    ->press('Enviar')
                    ->assertSee('Sala atualizada com sucesso');
        });

        // Restricao de bloqueio
        $this->browse(function (Browser $browser) {
            $browser->visit('/salas/1/edit')
                    ->click('#bloqueada-sim')
                    ->press('Enviar')
                    ->assertSee('Sala atualizada com sucesso');
        });

        // Responsavel
        $this->browse(function (Browser $browser) {
            $browser->visit('/salas/1/edit')
                ->click('#aprovacao-sim')
                ->type('#numero-usp', 1)
                ->press('Inserir')
                ->assertSee('adicionado como responsável');
        });
        $this->browse(function (Browser $browser) {
            $browser->visit('/salas/1/edit')
                ->click('#aprovacao-sim')
                ->click('.btn-danger')
                ->acceptDialog()
                ->assertSee('Responsável removido.');
        });


        // Antecedencia
        $this->browse(function (Browser $browser) {
            $browser->visit('/salas/1/edit')
                ->type('dias_antecedencia', '10')
                ->press('Enviar')
                ->assertSee('Sala atualizada com sucesso');
        });
        $this->browse(function (Browser $browser) {
            $browser->visit('/salas/1/edit')
                ->type('dias_antecedencia', '-10')
                ->press('Enviar')
                ->assertSee('Please enter a value greater than or equal to 0');
        });

        // Duracao
        $this->browse(function (Browser $browser) {
            $browser->visit('/salas/1/edit')
                ->type('duracao_minima', '-10')
                ->type('duracao_maxima', '-20')
                ->press('Enviar')
                ->assertSee('Please enter a value greater than or equal to 0. minutos')
                ->assertSee('Please enter a value greater than or equal to 1. minutos');
        });
        $this->browse(function (Browser $browser) {
            $browser->visit('/salas/1/edit')
                ->type('duracao_minima', '10')
                ->type('duracao_maxima', '20')
                ->press('Enviar')
                ->assertSee('Sala atualizada com sucesso');
        });
        $this->browse(function (Browser $browser) {
            $browser->visit('/salas/1/edit')
                ->type('duracao_minima', '20')
                ->type('duracao_maxima', '10')
                ->press('Enviar')
                ->assertPathIs('/salas/1/edit');
        });
        
        // Limite de datas
        $this->browse(function (Browser $browser) {
            $browser->visit('/salas/1/edit')
                ->select('tipo_restricao', 'AUTO')
                ->type('dias_limite', '0')
                ->press('Enviar')
                ->assertSee('Please enter a value greater than or equal to 1');
        });
        $this->browse(function (Browser $browser) {
            $browser->visit('/salas/1/edit')
                ->select('tipo_restricao', 'AUTO')
                ->type('dias_limite', '10')
                ->press('Enviar')
                ->assertSee('Sala atualizada com sucesso');
        });
        $this->browse(function (Browser $browser) {
            $browser->visit('/salas/1/edit')
                ->select('tipo_restricao', 'FIXA')
                ->type('data_limite', '01/01/2000')
                ->press('Enviar')
                ->assertSee('Please enter a value greater than or equal to');
        });
        $this->browse(function (Browser $browser) {
            $browser->visit('/salas/1/edit')
                ->select('tipo_restricao', 'FIXA')
                ->type('data_limite', '01/01/2050')
                ->press('Enviar')
                ->assertSee('Sala atualizada com sucesso');
        });

        // Periodo letivo - Necessario adicionar um em migrations para fazer os testes
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
