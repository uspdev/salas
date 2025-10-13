<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use App\Models\Sala;

class Recurso extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;

    use HasFactory;
    protected $guarded = ['id'];

    public function salas()
    {
        return $this->belongsToMany(Sala::class)
                    ->withTimestamps();
    }
}
