<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\V1\ReservaResource;
use App\Models\Finalidade;
use App\Models\Reserva;
use App\Models\Sala;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ReservaController extends Controller
{
    /**
     * Retorna todas as reservas com base nos filtros passados pelo método GET.
     * Se não for especificada data nem quantidade, serão retornadas todas as reservas do dia corrente.
     *
     * Cinco filtros estão disponíveis:
     * - sala        // Recebe o id da sala.
     * - categoria   // Recebe o id da categoria.
     * - finalidade  // Recebe o id da finalidade.
     * - data        // Deve estar no formato 'Y-m-d'
     * - quantidade  // Recebe um número inteiro, indicando a quantidade de reservas a ser retornada. Não se restringe ao dia corrente.
     *
     * @param Request $request
     *
     * @return object
     */
    public function getReservas(Request $request) : object {
       $reservas = Reserva::where('status', 'aprovada');

       if (!is_null($request->input('quantidade'))) {
          // obtém do dia de hoje em diante, ordena pelas mais próximas e limita a quantidade
          $reservas = $reservas->where('data', '>=', Carbon::now()->format('Y-m-d'))
                               ->orderBy('data', 'asc')
                               ->limit((int) $request->input('quantidade'));
       } else {
          // funcionamento original: obtém a data informada ou a data de hoje
          $data = is_null($request->input('data')) ? Carbon::now()->format('Y-m-d') : $request->input('data');
          $reservas = $reservas->where('data', $data);
       }

       if (!is_null($request->input('categoria'))) {
            $categoria_id = $request->input('categoria');
            $reservas = $reservas->whereHas('sala', function ($query) use ($categoria_id) {
                $query->where('categoria_id', $categoria_id);
            });
       }

       if(!is_null($request->input('finalidade'))){
         $reservas = $reservas->where('finalidade_id', $request->input('finalidade'));
       }

       if(!is_null($request->input('sala'))){
         $reservas = $reservas->where('sala_id', $request->input('sala'));
       }

       $reservas = $reservas->get();
       return ReservaResource::collection($reservas);
    }

    /**
     * Retorna o arquivo (imagem) mais antigo associado à reserva.
     *
     * @param Reserva $reserva
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse|\Illuminate\Http\JsonResponse
     */
    public function getReservaImagem(Reserva $reserva)
    {
        // obtém o arquivo mais antigo atrelado a esta reserva
        $arquivo = $reserva->arquivos()->orderBy('created_at', 'asc')->first();

        // se a reserva não possui arquivos, retorna um erro 404 amigável
        if (!$arquivo)
            return response()->json(['message' => 'Nenhuma imagem encontrada para esta reserva.'], 404);

        $caminhoLimpo = ltrim($arquivo->caminho, './');

        // verifica se o arquivo físico realmente existe na pasta storage/app
        if (!Storage::exists($caminhoLimpo))
            return response()->json(['message' => 'Arquivo físico não encontrado no servidor.'], 404);

        // retorna a imagem diretamente para o navegador/site consumir
        return Storage::response($caminhoLimpo);
    }
}
