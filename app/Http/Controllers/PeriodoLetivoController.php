<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PeriodoLetivo;
use App\Http\Requests\PeriodoLetivoRequest;

class PeriodoLetivoController extends Controller
{
    public function index()
    {
        $periodos = PeriodoLetivo::all();
        return view('periodos_letivos.index', compact('periodos'));
    }

    public function show($id)
    {
        $periodo = PeriodoLetivo::findOrFail($id);
        return view('periodos_letivos.show', compact('periodo'));
    }


    public function store(PeriodoLetivoRequest $request) {
        $this->authorize('admin');
        $periodo = PeriodoLetivo::create($request->validated());

        return redirect('/periodos_letivos')
            ->with('alert-sucess', 'Recurso criado com sucesso.');
    }

    public function destroy(PeriodoLetivo $periodo)
    {
        $this->authorize('admin');
        $periodo->delete();

        return redirect('/periodos_letivos')
            ->with('alert-sucess', 'Período Letivo excluído com sucesso.');
    }

}
