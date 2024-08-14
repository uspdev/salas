<?php

namespace App\Http\Resources\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CategoriaResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $vinculos = ['0' => 'Nenhum', '1' => 'Pessoas da unidade', '2' => 'Pessoas da USP'];

        return [
            'id' => $this->id,
            'nome' => $this->nome,
            'vinculo_cadastrado' => $vinculos[$this->vinculos],
            'setores_cadastrados' => $this->setores->pluck('nomset'),
            'salas' => SalaResource::collection($this->salas)
        ];
    }
}
