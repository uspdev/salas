<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use App\Models\Sala;

class Reserva extends Model
{
    use HasFactory;
    protected $guarded = ['id'];

    public function setDataAttribute($value) {
        $this->attributes['data'] = Carbon::createFromFormat('d/m/Y', $value);
    }

    public function getDataAttribute($value) {
        if($value)
          return Carbon::CreateFromFormat('Y-m-d', $value)->format('d/m/Y');
    }

    /**
     * O mutator getInicioAttribute foi criado para usarmos no FullCalendar
     * a junção da data com o horário, desta forma: $reserva->inicio
     */
    public function getInicioAttribute()
    {
        return Carbon::createFromFormat('d/m/Y H:i', $this->data .' '. $this->horario_inicio);
    }

    /**
     * O mutator getFimAttribute foi criado para usarmos no FullCalendar
     * a junção da data com o horário, desta forma: $reserva->fim
     */
    public function getFimAttribute()
    {
        return Carbon::createFromFormat('d/m/Y H:i', $this->data .' '. $this->horario_fim);
    }

    public static function salas(){
        $salas = Sala::all();
        return $salas;
    }

}
