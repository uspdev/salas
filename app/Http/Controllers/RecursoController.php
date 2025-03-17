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

        \UspTheme::activeUrl('recursos');
        return view('recurso.index', [
            'recursos' => $recursos,
        ]);
    }

    public function create()
    {
        $this->authorize('admin');

        $recurso = new Recurso();

        \UspTheme::activeUrl('recursos/create');
        return view('recurso.create', compact('recurso'));
    }

    public function edit(Recurso $recurso)
    {
        $this->authorize('admin');

        \UspTheme::activeUrl('recursos');
        return view('recurso.edit', compact('recurso'));
    }

    public function update(RecursoRequest $request, Recurso $recurso)
    {
        $this->authorize('admin');

        $validated = $request->validated();

        $recurso->nome = $validated['nome'];
        $recurso->save();

        \UspTheme::activeUrl('recursos');
        session()->put('alert-success', 'Recurso atualizado com sucesso.');
        return redirect()->route('recursos.index');
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

        if(Recurso::where('nome', $validated['nome'])->get()->isNotEmpty()) {
            \UspTheme::activeUrl('recursos/create');
            session()->put('alert-danger', 'Já existe recurso com este nome.');
            return redirect()->route('recursos.create')->withInput();
        }

        Recurso::create($validated);

        \UspTheme::activeUrl('recursos');
        session()->put('alert-success', 'Recurso criado com sucesso.');
        return redirect('/recursos');
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

        \UspTheme::activeUrl('recursos');
        session()->put('alert-success', 'Recurso excluído com sucesso.');
        return redirect('/recursos');
    }
}
