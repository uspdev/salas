<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Reserva;
use App\Models\Categoria;
use OwenIt\Auditing\Contracts\Auditable;

class Sala extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;
    
    use HasFactory;
    protected $guarded = ['id'];

    public function reservas()
    {
        return $this->hasMany(Reserva::class);
    }

    public function categoria()
    {
        return $this->belongsTo(Categoria::class);
    }
}