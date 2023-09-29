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

            if(Gate::allows('pessoa.unidade')) return Sala::find($sala_id)->categoria->vinculos == 1;
                
            if(Gate::allows('pessoa.usp')) return Sala::find($sala_id)->categoria->vinculos == 2;

            return false;
        });

        /**
         * Pessoas que possuem um dos três vínculos (Docente, Servidor ou Estagiário) ligadas à unidade.
         */
        Gate::define('pessoa.unidade', function(){
            return Gate::allows('senhaunica.docente') || Gate::allows('senhaunica.servidor') || Gate::allows('senhaunica.estagiario');
        });

        /**
         * Pessoas que possuem um dos três vínculos (Docente, Servidor ou Estagiário) ligadas à USP.
         */
        Gate::define('pessoa.usp', function(){
            return Gate::allows('senhaunica.docenteusp') || Gate::allows('senhaunica.servidorusp') || Gate::allows('senhaunica.estagiariousp');
        });

        /**
         * Pessoas que são responsáveis pela sala em questão.
         */
        Gate::define('responsavel', function($user, $sala_id){
            if(Gate::allows('admin')) return true;

            $sala = Sala::find($sala_id);

            foreach($sala->responsaveis as $responsavel){
                if($responsavel->user->id == $user->id) return true;
            }

            return false;
        });
    }
}
