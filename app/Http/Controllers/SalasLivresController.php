<?php

namespace App\Http\Controllers;

use App\Models\Sala;
use Carbon\Carbon;
use App\Http\Requests\SalaLivreRequest;
use Illuminate\Http\Request;

class SalasLivresController extends Controller
{
    public function index()
    {
        return view('sala.salas_livres', ['today' => Carbon::today()->format('d/m/Y')]);
    }

    //pega as reservas que não estão ocupadas nos horários solicitados
    public function search(Request $request)
    {
        $validacao = SalaLivreRequest::handle($request->horario_inicio, $request->horario_fim, $request->data);
        if(!$validacao){
            $salas = Sala::SalasLivresQuery($request->horario_inicio, $request->horario_fim,Carbon::createFromFormat('d/m/Y',$request->data)->format('Y-m-d'));
        }else{
            return $validacao;
        }
        
        if ($salas->isNotEmpty()) {
            return response()->json($salas);
        }else{
            return response()->json(['Nenhuma sala encontrada',404]);
        }
    }
}
