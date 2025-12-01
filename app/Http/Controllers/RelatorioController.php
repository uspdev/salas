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
        $this->authorize('admin');

        return view('relatorio.index', [
            'categorias' => Categoria::pluck('nome','id')->prepend('Selecione a Categoria', '')
        ]);
    }

    public function query(RelatorioRequest $request, Excel $excel){
        $this->authorize('admin');
        $inicio = Carbon::createFromFormat('d/m/Y', $request->inicio)->format('Y-m-d');
        $fim = Carbon::createFromFormat('d/m/Y', $request->fim)->format('Y-m-d');

        $reservas = Reserva::join('salas','reservas.sala_id','salas.id')
        ->where('salas.categoria_id', $request->categoria_id)
        ->select(
            'salas.nome as nome_sala',
            'reservas.nome',
            'reservas.descricao',
            'reservas.horario_inicio',
            'reservas.horario_fim',
            'reservas.data'
            )
        ->whereBetween('reservas.data', [$inicio, $fim])
        ->orderBy('data','asc')
        ->orderBy('horario_inicio','desc')
        ->toBase()
        ->get();

        if($reservas->isNotEmpty()){
            $data = $reservas->toArray();
            $headings = [
                'Sala',
                'Título da reserva',
                'Horário de início',
                'Horário de fim',
                'Descrição',
                'Data da reserva'
            ];
            $categoria = Categoria::where('id',$request->categoria_id)->first();
            $export = new RelatorioExport($data, $headings);
            return $excel->download($export, "Relatorio_$categoria->nome"."_$inicio-$fim.xlsx");
        }
        else{
            return back()->with('alert-danger','Não foi encontradas reservas no período selecionado')->withInput();
        }
    }

}
