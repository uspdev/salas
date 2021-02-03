<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Categoria;

class IndexController extends Controller
{
    public function index()
    {   
        return view('index',[
            'categorias' => Categoria::all()
        ]);
    }
}
