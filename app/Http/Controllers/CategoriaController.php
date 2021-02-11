<?php

namespace App\Http\Controllers;

use App\Models\Categoria;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Requests\CategoriaRequest;
use Illuminate\Support\Facades\Validator;
use Uspdev\Replicado\Pessoa;

class CategoriaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $categorias = Categoria::all();
        return view('categoria.index', [
            'categorias' => $categorias
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('categoria.create', [
            'categoria' => new Categoria,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CategoriaRequest $request)
    {
        $validated = $request->validated();
        $categoria = Categoria::create($validated);
        request()->session()->flash('alert-info', 'Categoria criada com sucesso.');
        return redirect("/categorias/{$categoria->id}");
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Categoria  $categoria
     * @return \Illuminate\Http\Response
     */
    public function show(Categoria $categoria)
    {
        return view('categoria.show',[
            'categoria' => $categoria
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Categoria  $categoria
     * @return \Illuminate\Http\Response
     */
    public function edit(Categoria $categoria)
    {
        return view('categoria.edit', [
            'categoria' => $categoria
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Categoria  $categoria
     * @return \Illuminate\Http\Response
     */
    public function update(CategoriaRequest $request, Categoria $categoria)
    {
        $validated = $request->validated();
        $categoria->update($validated);
        request()->session()->flash('alert-info', 'Categoria atualizada com sucesso.');
        return redirect("/categorias/{$categoria->id}");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Categoria  $categoria
     * @return \Illuminate\Http\Response
     */
    public function destroy(Categoria $categoria)
    {
        $categoria->delete();
        request()->session()->flash('alert-info', 'Categoria excluída com sucesso.');
        return redirect('/categorias');
    }

    public function adduser(Request $request, Categoria $categoria){
        # é um número USP válido?
        $pessoa = Pessoa::dump($request->codpes);
        if(!$pessoa) {
            dd('Não encontrei ess apessoa chara');
        }

        $user = User::where('codpes',$request->codpes)->first();
        if(!$user) {
            $user = new User;
            $user->codpes = $request->codpes;
            $user->name = $pessoa['nompes'];
            $user->email = Pessoa::emailusp($request->codpes);
            $user->save();
        }

        $categoria->users()->attach($user);
        return redirect("/categorias/{$categoria->id}");
    }
}
