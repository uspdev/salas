<?php

namespace App\Http\Controllers;

use App\Models\Arquivo;
use App\Models\Reserva;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;

class ArquivoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {}

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {}

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'arquivo.*' => 'required|mimes:jpeg,jpg,png|max:' . config('salas.upload_max_filesize'),
            'reserva_id' => 'required|integer|exists:reservas,id',
        ]);

        $reserva = Reserva::find($request->reserva_id);
        $this->authorize('owner', $reserva);
        $this->authorize('reserva.editar', $reserva);

        $arquivos_nome = [];
        foreach ($request->arquivo as $arq) {
            $arquivo = new Arquivo;
            $arquivo->reserva_id = $reserva->id;
            $arquivo->user_id = \Auth::user()->id;
            $arquivo->nome_original = $arq->getClientOriginalName();
            $arquivo->caminho = $arq->store('./arquivos/' . $reserva->created_at->year);
            $arquivo->mimeType = $arq->getClientMimeType();
            $arquivo->save();
            array_push($arquivos_nome, $arquivo->nome_original);
        }

        $request->session()->flash('alert-success', 'Imagem(ns) adicionada(s) com sucesso!');
        return Redirect::to(URL::previous() . "#card_arquivos");
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Arquivo  $arquivo
     * @return \Illuminate\Http\Response
     */
    public function show(Arquivo $arquivo)
    {
        while (ob_get_level() > 0)    // este while é para não estourar erro quando usando docker
            ob_end_clean();           // https://stackoverflow.com/questions/39329299/laravel-file-downloaded-from-storage-folder-gets-corrupted

        return Storage::download($arquivo->caminho, $arquivo->nome_original);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Arquivo  $arquivo
     * @return \Illuminate\Http\Response
     */
    public function edit(Arquivo $arquivo)
    {}

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Arquivo  $arquivo
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Arquivo $arquivo)
    {
        $this->authorize('owner', $arquivo->reserva);
        $this->authorize('reserva.editar', $arquivo->reserva);

        $request->validate(
            ['nome_arquivo' => 'required'],
            ['nome_arquivo.required' => 'O nome da imagem é obrigatório!']
        );
        $nome_antigo = $arquivo->nome_original;
        $extensao = pathinfo($nome_antigo, PATHINFO_EXTENSION);
        $arquivo->nome_original = $request->nome_arquivo . '.' . $extensao;
        $arquivo->update();

        request()->session()->flash('alert-success', 'Imagem renomeada com sucesso!');
        return Redirect::to(URL::previous() . "#card_arquivos");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Arquivo  $arquivo
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, Arquivo $arquivo)
    {
        $this->authorize('owner', $arquivo->reserva);
        $this->authorize('reserva.editar', $arquivo->reserva);

        if (Storage::exists($arquivo->caminho))
            Storage::delete($arquivo->caminho);

        $arquivo->delete();

        $request->session()->flash('alert-success', 'A imagem ' . $arquivo->nome_original . ' foi excluída com sucesso!');
        return Redirect::to(URL::previous() . "#card_arquivos");
    }
}
