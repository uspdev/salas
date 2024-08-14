<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\V1\CategoriaResource;
use App\Models\Categoria;
use Illuminate\Http\Request;

class CategoriaController extends Controller
{

    /**
     * Retorna todas as categorias.
     * 
     * @return object
     */
    public function index() : array {
        return ['data' => Categoria::all()->select(['id', 'nome'])];
    }

    /**
     * Retorna as informações e salas da categoria.
     * 
     * @param Categoria $categoria
     * 
     * @return object
     */
    public function show(Categoria $categoria) : object {
        return new CategoriaResource($categoria);
    }
}
