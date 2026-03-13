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

    public function testReservaBasica(): void
    {
        //descrever o que o teste faz. passo a passo...
        //este teste não pode depender do replicado
        // Login com usuário admin
        $this->browse(function (Browser $browser) {
            $browser->visit('/loginlocal')
                    ->typeSlowly('email', $this->adminUser->email)
                    ->typeSlowly('password', 'password')
                    ->press('Entrar')
                    ->pause(2000)
                    ->visit('/categorias/create')
                    ->pause(2000);
        });
    }
}
