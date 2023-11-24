<?php

namespace App\Providers;

use App\Models\Reserva;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use App\Models\Sala;
use Spatie\Permission\Models\Permission;

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

            if(Gate::allows('responsavel')) return true;

            if(Gate::allows('pessoa.unidade')) return Sala::find($sala_id)->categoria->vinculos == 1;
                
            if(Gate::allows('pessoa.usp')) return Sala::find($sala_id)->categoria->vinculos == 2;

            return Gate::allows('pessoa.setor', $sala->categoria);
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
        Gate::define('responsavel', function($user, $sala){
            if(Gate::allows('admin')) return true;

            foreach($sala->responsaveis as $responsavel){
                if($responsavel->id == $user->id) return true;
            }

            return false;
        });

        /**
         * Pessoas que pertencem à algum setor cadastrado na categoria.
         */
        Gate::define('pessoa.setor', function($user, $categoria){
            foreach ($categoria->setores as $setor) {
                $setorSigla = strtolower(explode('-', $setor['nomabvset'])[0]);

                $userHasEstagiarioPermission = Permission::where('name', 'Estagiario.'. $setorSigla)->exists() && $user->hasPermissionTo('Estagiario.'.$setorSigla, 'senhaunica');
                $userHasServidorPermission = Permission::where('name', 'Servidor.'. $setorSigla)->exists() && $user->hasPermissionTo('Servidor.'.$setorSigla, 'senhaunica');
                $userHasDocentePermission = Permission::where('name', 'Docente.'. $setorSigla)->exists() && $user->hasPermissionTo('Docente.'.$setorSigla, 'senhaunica');

                if($userHasEstagiarioPermission || $userHasServidorPermission || $userHasDocentePermission) return true;

            }

            return false;
        });

        /**
         * Reservas que podem ser editadas.
         */
        Gate::define('reserva.editar', function($user, $reserva){
            if(Gate::allows('admin')) return true;

            // Se a sala não necessita de aprovação retorna true.
            if(!$reserva->sala->aprovacao) return true;

            // Se a sala necessita de aprovação e está pendente retorna true.
            if($reserva->sala->aprovacao && $reserva->status == 'pendente') return true;

            return false;
        });
    }
}
