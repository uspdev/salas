<?php

namespace Tests\Browser;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class ReservaCrudTest extends DuskTestCase
{
    /**
     * A Dusk test example.
     */
    public function test_reserva_crud(): void
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
                ->pause(2300);

            $sala_id = \App\Models\Sala::select('id')->latest()->first();

            //3. Por fim, cria-se uma reserva inserindo a sala que desejamos.
            $browser->click('a[href="/reservas/create"]')
                ->pause(1500)
                ->typeSlowly('nome', 'Reserva Teste', 100)
                ->typeSlowly('data', now()->format('d/m/Y'), 50)
                ->typeSlowly('horario_inicio', '8:00', 50)
                ->typeSlowly('horario_fim', '10:00', 50)
                ->pause(1000)
                ->select('sala_id', $sala_id->id)
                ->pause(2000)
                ->clickAtXPath('//body') //clica fora do "calendário"
                ->pause(1000)
                ->radio('rep_bool', 'Não')
                ->pause(2000)
                ->press('Enviar')
                ->pause(5000)
                ->assertSee('Reserva Teste');

            $browser->click('a[title="Editar"]')
                ->pause(2000)
                ->typeSlowly('nome', 'Reserva Teste alterada')
                ->pause(1000)
                ->press('Enviar')
                ->pause(5000)
                ->assertSee('Reserva Teste alterada');

            $browser->click('button[title="Excluir"]')
                ->pause(2000)
                ->press("Excluir")
                ->pause(2000)
                ->assertSee('Reserva excluída com sucesso.');
        });
    }
}
