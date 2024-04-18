<?php

namespace Tests\Browser;

use Laravel\Dusk\Browser;
use Tests\DuskTestCase;


class HomeTest extends DuskTestCase {

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
            $browser->visit('/')
                    ->assertSee('Sistema Salas');
        });
        $this->browse(function (Browser $browser) {
            $browser->visit('/search')
                    ->assertSee('Sistema Salas');
        });
    }


    /**
     * Busca uma reserva valida
     *
     * @return void
     * @depends testAcessoComoNaoAdministrador
     */

    public function testBuscaReservaValida () {

        // Verifica a lista na pagina inicial 
        $this->browse(function (Browser $browser) {
            $browser->visit('/search')
                    ->assertSee('Aula FLP32020');
        });

        // Busca pelos filtros corretos
        $this->browse(function (Browser $browser) {
            $browser->visit('/search')
                    ->select('filter[]', 1)
                    ->select('finalidades_filter[]', 1)
                    ->select('salas_filter[]', 1)
                    ->type('#input_busca_data', '14/01/2021')
                    ->type('#input_busca_nome', 'Aula FLP32020')
                    ->click('#input_busca_nome')
                    ->click('#buscar_reservas')
                    ->assertSee('Aula de Política III do ano de 2020.');
        });
    }


    /**
     * Busca uma reserva invalida
     *
     * @return void
     * @depends testBuscaReservaValida
     */
    public function testBuscaReservaInvalida () {
        // Busca pelos filtros incorretos
        $this->browse(function (Browser $browser) {
            $browser->visit('/search')
                    ->select('filter[]', 2)
                    ->select('finalidades_filter[]', 2)
                    ->select('salas_filter[]', 2)
                    ->type('#input_busca_data', '14/01/2050')
                    ->type('#input_busca_nome', 'Aula FLP32020X')
                    ->click('#input_busca_nome')
                    ->click('#buscar_reservas')
                    ->assertSee('Não há reservas registradas.');
        });
    }
}