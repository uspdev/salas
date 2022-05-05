<?php

namespace Tests\Browser;

use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class RecursoTest extends DuskTestCase
{
    /**
     * Testa o formulário de adição de uma instância Recurso.
     * O único campo disponível é "nome".
     *
     * @return void
     */
    public function testCreateRecurso()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/recursos')
                    ->typeSlowly('nome', 'Data show')
                    ->press('Enviar')
                    ->assertPathIs('/recursos')
                    ->assertSee('Data show');
        });
    }

    /**
     * Testa o formulário de adição no qual o input é nulo.
     *
     * @return void
     */
    public function testInputNull()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/recursos')
                    ->press('Enviar')
                    ->assertSee('O nome não pode ficar em branco.');
        });
    }

    /**
     * Testa se a deleção de um recurso tem um popup (dialog) de confirmação.
     *
     * @return void
     */
    public function testDeleteDialog()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/recursos')
                    ->press('ul.list-group:nth-child(3) > li:nth-child(1) > form:nth-child(1) > button:nth-child(3)')
                    ->assertDialogOpened('Tem certeza?');
        });
    }
}
