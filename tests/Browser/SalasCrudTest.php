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
                ->click('#navbarDropdowniclassfafausercogariahiddentrueiAdministrao') //menu
                ->pause(1250)
                ->click('a[href="categorias/create"]')
                ->pause(1250)
                ->typeSlowly('nome', 'Prédio da Administração', 100)
                ->pause(2000)
                ->press('Enviar')
                ->pause(2000);

            /*Buscando no banco de dados a categoria que acabou de ser criada.*/
            $categoria_id = \App\Models\Categoria::select('id')->latest()->first();

            //2. Após a criação da categoria, cria-se uma sala com ela.
            $browser->click('#navbarDropdowniclassfafausercogariahiddentrueiAdministrao') //menu
                ->pause(1850)
                ->click('a[href="salas/create"]')
                ->pause(1250)
                ->typeSlowly('nome', 'Sala de Informática Teste', 100)
                ->typeSlowly('capacidade', '123', 150)
                ->select('categoria_id', $categoria_id->id) //Selecionando o ID da cat. criada
                ->pause(2300)
                ->press('Enviar')
                ->pause(2300)
                ->assertSee('Sala criada com sucesso');

            $sala_id = \App\Models\Sala::select('id')->latest()->first();

            $browser->click('#navbarDropdowniclassfafausercogariahiddentrueiAdministrao')
                ->click('a[href="salas/listar"]')
                ->assertSee('Sala de Informática Teste')
                ->pause(2000)
                ->click("a[href='http://salas/salas/{$sala_id->id}/edit']")
                ->typeSlowly('nome', 'Sala de Informática Teste alterada.')
                ->press('Enviar')
                ->assertSee('Sala atualizada com sucesso');

            $browser->click('#navbarDropdowniclassfafausercogariahiddentrueiAdministrao')
                ->click('a[href="salas/listar"]')
                ->assertSee('Sala de Informática Teste alterada.')
                ->press("button[data-sala-id='{$sala_id->id}']")
                ->acceptDialog()
                ->pause(2000)
                ->assertSee('Sala excluída com sucesso');
        });
    }
}
