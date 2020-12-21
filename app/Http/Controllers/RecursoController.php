<?php

namespace App\Http\Controllers;

use App\Models\Recurso;
use Illuminate\Http\Request;
use App\Http\Requests\RecursoaRequest;
use Illuminate\Support\Facades\Validator;

class RecursoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $recursos = Recurso::all();
        return view('recurso.index', [
            'recursos' => $recursos
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('recurso.create', [
            'recurso' => new Recurso,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(RecursoRequest $request)
    {
        $validated = $request->validated();
        $recurso = Recurso::create($validated);
        request()->session()->flash('alert-info', 'Recurso criada com sucesso.');
        return redirect("/recursos/{$recurso->id}");
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Recurso  $recurso
     * @return \Illuminate\Http\Response
     */
    public function show(Recurso $recurso)
    {
        return view('recurso.show',[
            'recurso' => $recurso
            ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Recurso  $recurso
     * @return \Illuminate\Http\Response
     */
    public function edit(Recurso $recurso)
    {
        return view('recurso.edit', [
            'recurso' => $recurso
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Recurso  $recurso
     * @return \Illuminate\Http\Response
     */
    public function update(RecursoRequest $request, Recurso $recurso)
    {
        $validated = $request->validated();
        $recurso->update($validated);
        request()->session()->flash('alert-info', 'Recurso atualizada com sucesso.');
        return redirect("/recursos/{$recurso->id}");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Recurso  $recurso
     * @return \Illuminate\Http\Response
     */
    public function destroy(Recurso $recurso)
    {
        $recurso->delete();
        request()->session()->flash('alert-info', 'Recurso excluída com sucesso.');
        return redirect('/recursos');
    }
}
