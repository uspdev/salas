<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Reserva;
use App\Models\Categoria;

class Sala extends Model
{
    use HasFactory;
    protected $guarded = ['id'];

    public function reservas()
    {
        return $this->hasMany(Reserva::class);
    }

    public static function categorias()
    {
        $categorias = Categoria::all();
        return $categorias;
    }

    public function categoria()
    {
        return $this->belongsTo(Categoria::class);
    }
}