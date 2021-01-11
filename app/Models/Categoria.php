<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Sala;

class Categoria extends Model
{
    use HasFactory;
    protected $guarded = ['id'];

    public function salas(){
        return $this->hasMany(Sala::class);
    }
}
