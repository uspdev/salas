<?php

namespace App\Http\Controllers;

use App\Models\Categoria;
use App\Models\Reserva;
use Carbon\Carbon;
use Maatwebsite\Excel\Excel;
use App\Exports\RelatorioExport;
use App\Http\Requests\RelatorioRequest;

class RelatorioController extends Controller
{
    public function index(){
        return view('relatorio.index', ['categorias' => Categoria::all('id','nome')]);
    }

    public function query(RelatorioRequest $request, Excel $excel){
        $inicio = Carbon::createFromFormat('d/m/Y', $request->inicio)->format('Y-m-d');
        $fim = Carbon::createFromFormat('d/m/Y', $request->fim)->format('Y-m-d');

        $reservas = Reserva::join('salas','reservas.sala_id','salas.id')
        ->where('salas.categoria_id', $request->categoria_id)
        ->whereBetween('reservas.data', [$inicio, $fim])
        ->orderBy('data','asc')
        ->get();
        
        if($reservas->isNotEmpty()){
            $nomes_salas = $reservas->select('nome')->groupBy('nome');
            $reservas = $reservas->select('nome','data')->groupBy('data');
            
            /** retorna o nome das salas no excel */
            $data = $reservas->map(function ($item){
                return $item->pluck('nome')->unique(); //o unique impede repetição do nome devido a sala ser usada em mais de um horário
            });

            /** cabeçalho repetindo "Sala" conforme número de registros */
            foreach($nomes_salas->keys() as $nome_sala){
                $sala[] = 'Sala';
            }

            $headings = array_merge(['Data da Reserva'],$sala);
            $categoria = Categoria::where('id',$request->categoria_id)->first();
            $export = new RelatorioExport($data, $headings);
            return $excel->download($export, "Relatorio_$categoria->nome"."_$inicio-$fim.xlsx");
        }
        else{
            return back()->with('alert-danger','Não foi encontradas reservas no período selecionado')->withInput();
        }
    }

}
