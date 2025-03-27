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

        \UspTheme::activeUrl('periodos_letivos');
        return view('periodos_letivos.index', compact('periodos'));
    }

    public function show($id)
    {
        $periodo = PeriodoLetivo::findOrFail($id);

        \UspTheme::activeUrl('periodos_letivos');
        return view('periodos_letivos.show', compact('periodo'));
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $this->authorize('admin');

        \UspTheme::activeUrl('periodos_letivos/create');
        return view('periodos_letivos.create', [
            'periodo' => new PeriodoLetivo()
        ]);
    }



/**
     * Show the form for editing the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function edit(PeriodoLetivo $periodo)  // atenção: o nome do atributo 'periodo' está mapeado no arquivo de rotas
    {
        $this->authorize('admin');

        \UspTheme::activeUrl('periodos_letivos');
        return view('periodos_letivos.edit', compact('periodo'));
    }



    public function store(PeriodoLetivoRequest $request) {
        $this->authorize('admin');
        $periodo = PeriodoLetivo::create($request->validated());

        \UspTheme::activeUrl('periodos_letivos');
        session()->put('alert-success', 'Recurso criado com sucesso.');
        return redirect('/periodos_letivos');
    }

    public function destroy(PeriodoLetivo $periodo)
    {
        $this->authorize('admin');

        if ($periodo->restricao->isNotEmpty()) {
            $nomesSalas = "";

            foreach ($periodo->restricao as $restricao) {
                $nomesSalas .= $restricao->sala->nome . "<br>";
              }

              \UspTheme::activeUrl('periodos_letivos');
              session()->put('alert-danger', "Período Letivo não pôde ser excluído pois está associado a restrições da(s) sala(s): <br> $nomesSalas ");
              return redirect('/periodos_letivos');

        } else {
            $periodo->delete();

            \UspTheme::activeUrl('periodos_letivos');
            session()->put('alert-success', 'Período Letivo excluído com sucesso.');
            return redirect('/periodos_letivos');
        }

    }



    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function update(PeriodoLetivoRequest $request, PeriodoLetivo $periodo)
    {
        $this->authorize('admin');

        $validated = $request->validated();

        $periodo->update($validated);

        \UspTheme::activeUrl('periodos_letivos');
        session()->put('alert-success', 'Periodo letivo atualizada com sucesso');
        return redirect("/periodos_letivos");
    }

}
