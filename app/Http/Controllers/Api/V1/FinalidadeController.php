<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Finalidade;
use Illuminate\Http\Request;

class FinalidadeController extends Controller
{
    public function index() : object {
        return Finalidade::all()->select('id', 'legenda');
    }
}
