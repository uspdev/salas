<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Responsavel extends Model
{
    use HasFactory;

    protected $table = 'responsaveis';
    protected $guarded = ['id'];

    public function salas()
    {
        return $this->belongsToMany(Sala::class);
    }
}
