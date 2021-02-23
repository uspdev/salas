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
        $this->authorize('admin');
        
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
        $this->authorize('admin');

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
        $this->authorize('admin');

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
        $this->authorize('admin');
        
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
        $this->authorize('admin');

        $categoria->delete();
        request()->session()->flash('alert-info', 'Categoria excluída com sucesso.');
        return redirect('/categorias');
    }

    public function addUser(Request $request, Categoria $categoria)
    {
        $this->authorize('admin');

        # é um número USP válido?
        $pessoa = Pessoa::dump($request->codpes);
        if(!$pessoa) {
            /* dd('Não encontrei ess apessoa chara') */
            request()->session()->flash('alert-danger', 'Número USP inválido');
            return redirect("/categorias/{$categoria->id}");
        }

        # Cria um objeto para o usuário em questão
        $user = User::where('codpes',$request->codpes)->first();
        if(!$user) {
            $user = new User;
            $user->codpes = $request->codpes;
            $user->name = $pessoa['nompes'];
            $user->email = Pessoa::emailusp($request->codpes);
            $user->save();
        }

        # não pode existir na tabela categoria_users uma instância
        # com o user_id e a categoria_id solicitados.
        if(!$categoria->users->contains($user)) {
            $categoria->users()->attach($user);
            request()->session()->flash('alert-success', "{$user->name} cadastrado(a) em {$categoria->nome}");
        }
        else {
            request()->session()->flash('alert-warning', "{$user->name} já está cadastrado(a) em {$categoria->nome}");
        }
        
        return redirect("/categorias/{$categoria->id}");
    }

    public function removeUser(Request $request, Categoria $categoria, User $user)
    {    
        $this->authorize('admin');

        $categoria->users()->detach($user->id);
        request()->session()->flash('alert-danger', "{$user->name} foi excluído(a) de {$categoria->nome}");
        return redirect("/categorias/{$categoria->id}");
    }
}
