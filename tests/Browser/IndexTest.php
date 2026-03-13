<?php

namespace Tests\Browser;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

use Illuminate\Support\Facades\Artisan;

use App\Models\User;
use Illuminate\Support\Facades\Hash;

use Spatie\Permission\Models\Permission;

use App\Helpers\UspdevDuskTrait;

class IndexTest extends DuskTestCase
{
    use UspdevDuskTrait;

    protected function setUp(): void
    {
        parent::setUp();
        Artisan::call('migrate:fresh');

        $this->setupAdminAndUser();
    }

    public function testLoginLogout()
    {
        $this->browse(function (Browser $browser) {
            // Login com usuário comum
            $browser->visit('/loginlocal')
                    ->typeSlowly('email', $this->commonUser->email)
                    ->typeSlowly('password', 'password')
                    ->press('Entrar')
                    ->pause(2000)
                    ->click('.login_logout_link')
                    ->pause(2000);

            // Login com usuário admin,
            $browser->visit('/loginlocal')
                    ->typeSlowly('email', $this->adminUser->email)
                    ->typeSlowly('password', 'password')
                    ->press('Entrar')
                    ->pause(2000)
                    ->click('.login_logout_link')
                    ->pause(2000);
        });
    }

    /*
    public function testCompleto()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/loginlocal')
                    ->typeSlowly('email', $this->adminUser->email)
                    ->typeSlowly('password', 'password')
                    ->press('Entrar')
                    ->pause(2000)
                    ->clickLink('Administração')
                    ->clickLink('Cadastrar Categoria')
                    ->pause(2000)
                    ->typeSlowly('nome', 'Prédio de Administração')
                    ->pause(1000)
                    ->press('Enviar');
                    #->click('.login_logout_link')
                    #->pause(2000);
        });
    }
    */
}
