<?php

namespace App\Http\Resources\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SalaResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'nome' => $this->nome,
            'id_categoria' => $this->categoria->id,
            'categoria' => $this->categoria->nome,
            'capacidade' => $this->capacidade,
            'recursos' => $this->recursos->pluck('nome')
        ];
    }
}
