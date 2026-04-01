<?php

namespace Tests\Browser;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;
use App\Helpers\UspdevDuskTrait;

class ReservaBasicaTest extends DuskTestCase
{
    use UspdevDuskTrait;
    /**
     * A Dusk test example.
     */

    protected function setUp(): void
    {
        parent::setUp();
        $this->setupAdminAndUser(); // cria usuários $this->commonUser e $this->adminUser
    }

    /*Teste para criar uma reserva básica: 
    - 1. Criar uma Categoria (Prédio);
    - 2. Criar Sala dentro de uma categoria;
    - 3. Cadastrar Reserva contendo a sala;
    */
    public function testReservaBasica(): void
    {
        //1. Login como admin e criação de uma categoria (prédio)
        $this->browse(function (Browser $browser) {
            $browser->visit('/loginlocal')
                    ->typeSlowly('email', $this->adminUser->email, 30)
                    ->typeSlowly('password', 'password', 30)
                    ->press('Entrar')
                    ->pause(1000)
                    ->clickLink('Administração')
                    ->pause(1250)
                    ->clickLink('Cadastrar Categoria')
                    ->pause(1250)
                    ->typeSlowly('nome','Prédio da Administração', 100)
                    ->pause(2000)
                    ->press('Enviar')
                    ->pause(2000);

                    /*Buscando no banco de dados a categoria que acabou de ser criada.*/
                    $categoria_id = \App\Models\Categoria::select('id')->latest()->first();
                    
                    //2. Após a criação da categoria, cria-se uma sala com ela.
                    $browser->clickLink('Administração')
                    ->pause(1850)
                    ->clickLink('Cadastrar Sala')
                    ->pause(1250)
                    ->typeSlowly('nome','Sala de Informática Teste', 100)
                    ->typeSlowly('capacidade','123', 150)
                    ->select('categoria_id', $categoria_id->id) //Selecionando o ID da cat. criada
                    ->pause(2300)
                    ->press('Enviar')
                    ->pause(2300);
                    
                    $sala_id = \App\Models\Sala::select('id')->latest()->first();

                    //3. Por fim, cria-se uma reserva inserindo a sala que desejamos.
                    $browser->clickLink('Nova reserva')
                    ->pause(1500)
                    ->typeSlowly('nome','Reunião DUSK STI', 100)
                    ->typeSlowly('data',now()->format('d/m/Y'), 50)
                    ->typeSlowly('horario_inicio','8:00', 50)
                    ->typeSlowly('horario_fim','10:00', 50)
                    ->pause(1000)
                    ->select('sala_id',$sala_id->id)
                    ->pause(2000)
                    ->clickAtXPath('//body') //clica fora do "calendário"
                    ->pause(1000)
                    ->radio('rep_bool','Não')
                    ->pause(2000)
                    ->press('Enviar')
                    ->pause(5000);
        });
    }
}
