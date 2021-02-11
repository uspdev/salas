<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use App\Models\Sala;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        // 'App\Models\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        Gate::define('logado', function ($user) {
            return true;
        });

        Gate::define('admin', function ($user) {
            $admins = explode(',', trim(env('ADMINS')));
            return in_array($user->codpes, $admins);
        });

        /** 
         * As pessoas só podem editar e excluir reservas feitas por elas mesma
         **/
        Gate::define('owner', function ($user, $instance) {
            if(Gate::allows('admin')) return true;
            if($instance->user_id == $user->id) return true;
            return false;
        });

        /**
         * Pessoas numa mesma categoria podem criar reservas nas salas
         * daquela categoria
         */
        Gate::define('members', function ($user, $sala_id) {
            if(Gate::allows('admin')) return true;
            /* o $user está numa categoria que tem a sala_id? */
            $sala = Sala::find($sala_id);
            foreach($user->categorias as $categoria){
                if( $sala->categoria->id == $categoria->id ) return true;
            }
            return false;
        });
    }
}
