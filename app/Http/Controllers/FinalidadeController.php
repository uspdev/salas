<?php

namespace App\Http\Controllers;

use App\Http\Requests\FinalidadeRequest;
use App\Models\Finalidade;
use Illuminate\Http\Request;

class FinalidadeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $finalidades = Finalidade::all();
        return view('settings.finalidades.index', compact('finalidades'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create () {
        $this->authorize('admin');
        return view('settings.finalidades.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Requests\FinalidadeRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(FinalidadeRequest $request)
    {
        $this->authorize('admin');
        $validated = $request->validated();
        $finalidade = Finalidade::create($validated);

        return redirect()->route('finalidades.index')
            ->with('alert-success', 'Finalidade criada com sucesso.');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Finalidade  $finalidade
     * @return \Illuminate\Http\Response
     */
    public function edit(Finalidade $finalidade)
    {
        $this->authorize('admin');
        return view('settings.finalidades.edit', compact('finalidade'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\FinalidadeRequest  $request
     * @param  \App\Models\Finalidade  $finalidade
     * @return \Illuminate\Http\Response
     */
    public function update(FinalidadeRequest $request, Finalidade $finalidade)
    {
        $this->authorize('admin');
        $validated = $request->validated();
        $finalidade->update($validated);

        return redirect()->route('finalidades.index')
            ->with('alert-success', 'Finalidade atualizada com sucesso.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Finalidade  $finalidade
     * @return \Illuminate\Http\Response
     */
    public function destroy(Finalidade $finalidade)
    {
        $this->authorize('admin');
        $finalidade->delete();
        return redirect()->route('finalidades.index')
            ->with('alert-success', 'Finalidade excluída com sucesso.');
    }
}
