<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Responsavel extends Model
{
    use HasFactory;

    protected $table = 'responsaveis';
    protected $guarded = ['id'];

    public function sala()
    {
        return $this->hasOne(Sala::class, 'id', 'sala_id');
    }

    public function user()
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }
}
