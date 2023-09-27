<?php

namespace App\Http\Controllers;

use App\Models\Responsavel;
use App\Models\Sala;
use App\Models\User;
use Illuminate\Http\Request;
use Uspdev\Replicado\Pessoa;

class ResponsavelController extends Controller
{
    public function store(Request $request){
        $this->authorize('admin');

        $codpes = $request->input('codpes');
        $sala = Sala::find($request->input('sala'));

        if(count(User::where('codpes', $codpes)->get()) == 0)
        {
            $user = User::findOrCreateFromReplicado($codpes);
            if (!($user instanceof \App\Models\User)) {
                return redirect()->back()->withErrors(['codpes' => $user]);
            }
        }else{
            $user = User::firstWhere('codpes', $codpes);
        }

        $responsavel = new Responsavel();
        $responsavel->sala_id = $sala->id;
        $responsavel->user_id = $user->id;
        $responsavel->save();

        $sala->aprovacao = 1;
        $sala->save();

        return redirect()->route('salas.edit',['sala' => $request->input('sala'), 'responsaveis' => $sala->responsaveis])->with('alert-success', $user->name.' adicionado como responsável.');
    }

    public function destroy(Responsavel $responsavel){
        $this->authorize('admin');

        $responsavel->delete();

        return back()->with('alert-success', 'Responsável removido.');
    }
}
