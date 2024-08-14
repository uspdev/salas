<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Finalidade;
use Illuminate\Http\Request;

class FinalidadeController extends Controller
{
    /**
     * Retorna todas as finalidades.
     * 
     * @return array
     */
    public function index() : array {
        return ['data' => Finalidade::all()->select('id', 'legenda')];
    }
}
