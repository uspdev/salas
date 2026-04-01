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

class LoginLogoutTest extends DuskTestCase
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
                    ->assertDontSee('Administração')
                    ->clickLink('Sair') //1. usar texto "sair" ao inves da classe
                    ->pause(2000);

            // Login com usuário admin
            $browser->visit('/loginlocal')
                    ->typeSlowly('email', $this->adminUser->email)
                    ->typeSlowly('password', 'password')
                    ->press('Entrar')
                    ->pause(2000)
                    ->assertSee('Administração')
                    ->clickLink('Sair')
                    ->pause(2000);
        });
    }

}
