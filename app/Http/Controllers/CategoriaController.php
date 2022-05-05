<?php

namespace App\Http\Controllers;

use App\Http\Requests\CategoriaRequest;
use App\Models\Categoria;
use App\Models\User;
use Illuminate\Http\Request;
use Uspdev\Replicado\Pessoa;

class CategoriaController extends Controller
{
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $this->authorize('admin');

        return view('categoria.create', [
            'categoria' => new Categoria(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function store(CategoriaRequest $request)
    {
        $this->authorize('admin');

        $categoria = Categoria::create($request->validated());

        return redirect("/categorias/{$categoria->id}")
            ->with('alert-sucess', 'Categoria criada com sucesso.');
    }

    /**
     * Display the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function show(Categoria $categoria)
    {
        return view('categoria.show', [
            'categoria' => $categoria,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function edit(Categoria $categoria)
    {
        $this->authorize('admin');

        return view('categoria.edit', [
            'categoria' => $categoria,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function update(CategoriaRequest $request, Categoria $categoria)
    {
        $this->authorize('admin');

        $categoria->update($request->validated());

        return redirect("/categorias/{$categoria->id}")
            ->with('alert-sucess', 'Categoria atualizada com sucesso.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy(Categoria $categoria)
    {
        $this->authorize('admin');

        $categoria->delete();

        return redirect('/salas')
            ->with('alert-sucess', 'Categoria excluída com sucesso.');
    }

    public function addUser(Request $request, Categoria $categoria)
    {
        $this->authorize('admin');

        $request->validate([
            'codpes' => 'required',
        ],
        [
            'codpes.required' => 'Entre com o número USP.',
        ]);

        // é um número USP válido?
        $pessoa = Pessoa::dump($request->codpes);

        if (!$pessoa) {
            return redirect("/categorias/{$categoria->id}")
                ->with('alert-danger', 'Número USP inválido');
        }

        // Cria um objeto User para $pessoa
        $user = User::firstOrCreate([
            'codpes' => $pessoa['codpes'],
            'name' => $pessoa['nompes'],
            'email' => Pessoa::emailusp($pessoa['codpes']),
        ]);

        // não pode existir na tabela categoria_users uma instância
        // com o user_id e a categoria_id solicitados.
        if (!$categoria->users->contains($user)) {
            $categoria->users()->attach($user);
            request()->session()->flash('alert-success', "{$user->name} cadastrado(a) em {$categoria->nome}");
        } else {
            request()->session()->flash('alert-warning', "{$user->name} já está cadastrado(a) em {$categoria->nome}");
        }

        return redirect("/categorias/{$categoria->id}");
    }

    public function removeUser(Request $request, Categoria $categoria, User $user)
    {
        $this->authorize('admin');

        $categoria->users()->detach($user->id);

        return redirect("/categorias/{$categoria->id}")
            ->with('alert-sucess', "{$user->name} foi excluído(a) de {$categoria->nome}");
    }
}
