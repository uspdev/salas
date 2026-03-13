<?php

namespace App\Helpers;

use App\Models\User;
use Spatie\Permission\Models\Permission;

/**
 * Trait SetupPermissions
 * * Esta trait centraliza a criação de usuários e permissões de teste.
 * É essencial para evitar o erro de Guard (senhaunica-socialite),
 * garantindo que os usuários do Dusk sempre tenham as permissões corretas
 * vinculadas ao guard correto antes da execução dos testes no universo usp.
 */
trait UspdevDuskTrait
{
    protected $adminUser;
    protected $commonUser;

    protected function setupAdminAndUser()
    {
        Permission::firstOrCreate(['name' => 'admin', 'guard_name' => 'senhaunica']);
        Permission::firstOrCreate(['name' => 'user', 'guard_name' => 'senhaunica']);

        $this->commonUser = User::firstOrCreate(
            ['email' => 'user@test.com'],
            ['name' => 'Dusk User', 'password' => bcrypt('password')]
        );
        $this->commonUser->givePermissionTo('user');

        $this->adminUser = User::firstOrCreate(
            ['email' => 'admin@test.com'],
            ['name' => 'Dusk Admin', 'password' => bcrypt('password')]
        );
        $this->adminUser->givePermissionTo('admin');
    }
}