<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Arquivo extends Model
{
    use HasFactory;

    /**
     * relacionamento com reserva
     */
    public function reserva()
    {
        return $this->belongsTo(Reserva::class);
    }

    /**
     * relacionamento com user
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
