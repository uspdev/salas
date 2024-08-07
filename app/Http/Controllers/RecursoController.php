<?php

namespace App\Http\Controllers;

use App\Http\Requests\RecursoRequest;
use App\Models\Recurso;

class RecursoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->authorize('admin');
        $recursos = Recurso::all();

        return view('recurso.index', [
            'recursos' => $recursos,
        ]);
    }

    public function create()
    {
        $this->authorize('admin');

        $recurso = new Recurso();

        return view('recurso.create', compact('recurso'));
    }

    public function edit(Recurso $recurso)
    {
        $this->authorize('admin');

        return view('recurso.edit', compact('recurso'));
    }

    public function update(RecursoRequest $request, Recurso $recurso)
    {
        $this->authorize('admin');

        $validated = $request->validated();

        $recurso->nome = $validated['nome'];
        $recurso->save();

        return redirect()->route('recursos.index')->with('alert-success', 'Recurso atualizado com sucesso.');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function store(RecursoRequest $request)
    {
        $this->authorize('admin');

        $validated = $request->validated();

        if(Recurso::where('nome', $validated['nome'])->get()->isNotEmpty())
            return redirect()->route('recursos.create')->with('alert-danger', 'Já existe recurso com este nome.')->withInput();

        Recurso::create($validated);

        return redirect('/recursos')
            ->with('alert-success', 'Recurso criado com sucesso.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy(Recurso $recurso)
    {
        $this->authorize('admin');
        $recurso->delete();

        return redirect('/recursos')
            ->with('alert-success', 'Recurso excluído com sucesso.');
    }
}
