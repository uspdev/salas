<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\V1\SalaResource;
use App\Models\Sala;
use Illuminate\Http\Request;

class SalaController extends Controller
{
    /**
     * Retorna todas as salas.
     * 
     * @return object
     */
    public function index() : object {
       return SalaResource::collection(Sala::all());
    }

    /**
     * Retorna as informações da sala.
     * 
     * @param Sala $sala
     * 
     * @return object
     */
    public function show(Sala $sala) : object {
        return new SalaResource($sala);
    }
}
