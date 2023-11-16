<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Restricao extends Model
{
    use HasFactory;

    protected $table = 'restricoes';
    protected $guarded = ['id'];

    protected $fillable = [
        'tipo',
        'data_limite',
        'dias_limite',
        'dias_antecedencia',
        'bloqueada',
        'sala_id'
    ];


    public function sala()
    {
        return $this->hasOne(Sala::class, 'id', 'sala_id');
    }


}
