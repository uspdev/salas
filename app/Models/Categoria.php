<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Categoria extends Model
{
    use HasFactory;
    protected $guarded = ['id'];

    public function salas()
    {
        return $this->hasMany(Sala::class);
    }

    public function users()
    {
        return $this->belongsToMany('App\Models\User', 'categoria_user')->withTimestamps();
    }

}
