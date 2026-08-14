<?php

namespace Tests\Browser;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class SalasCrudTest extends DuskTestCase
{
    /**
     * A Dusk test example.
     */
    public function test_salas_crud(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/')
                ->clickLink('Entrar')
                ->waitFor('#loginUsuario') # Importante: Espera a página de login carregar
                ->typeSlowly('loginUsuario', '1111')
                ->press('Login')
                ->waitFor('.login_logout_link')
                ->assertSee('ADMINISTRAÇÃO')
                ->clickLink('Administração')
                ->pause(2000)
                ->clickLink('Cadastrar Categoria')
                ->typeSlowly('nome', 'Categoria Teste')
                ->press('Enviar')
                ->assertSee('ADMINISTRAÇÃO')
                ->clickLink('Administração')
                ->pause(2000)
                ->clickLink('Cadastrar Sala')
                ->typeSlowly('nome', 'Sala Teste')
                ->typeSlowly('capacidade', '40')
                ->click('span[title="Selecione um opção"]')
                ->pause(2000)
                ->click('Categoria Teste')
                ->pause(2000)
                ->press('Enviar');
        });
    }
}
