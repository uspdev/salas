<?php

namespace App\Http\Resources\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ReservaResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'nome' => $this->nome,
            'sala' => $this->sala->nome,
            'data' => $this->data,
            'horario_inicio' => $this->horario_inicio,
            'horario_fim' => $this->horario_fim,
            'finalidade' => $this->finalidade->legenda,
            'descricao' => $this->descricao,
            'cadastrada_por' => $this->user->name,
            'status' => $this->status,
        ];
    }
}
