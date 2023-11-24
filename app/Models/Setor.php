<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Setor extends Model
{
    use HasFactory;

    protected $table = 'setores';

    public function categorias()
    {
        return $this->belongsToMany(Categoria::class, 'categoria_setor');
    }
}
