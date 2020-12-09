<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Reserva extends Model
{
    use HasFactory;
    protected $guarded = ['id'];

    public function setDataInicioAttribute($value) {
        $this->attributes['data_inicio'] = Carbon::createFromFormat('d/m/Y', $value);
    }

    public function getDataInicioAttribute($value) {
        if($value)
          return Carbon::CreateFromFormat('Y-m-d', $value)->format('d/m/Y');
    }

    public function setDataFimAttribute($value) {
        $this->attributes['data_fim'] = Carbon::createFromFormat('d/m/Y', $value);
    }

    public function getDataFimAttribute($value) {
        if($value)
          return Carbon::CreateFromFormat('Y-m-d', $value)->format('d/m/Y');
    }

    /**
     * O mutator getInicioAttribute foi criado para usarmos no FullCalendar
     * a junção da data com o horário, desta forma: $reserva->inicio
     */
    public function getInicioAttribute()
    {
        return Carbon::createFromFormat('d/m/Y H:i:s', $this->data_inicio .' '. $this->horario_inicio);
    }

    /**
     * O mutator getFimAttribute foi criado para usarmos no FullCalendar
     * a junção da data com o horário, desta forma: $reserva->fim
     */
    public function getFimAttribute()
    {
        return Carbon::createFromFormat('d/m/Y H:i:s', $this->data_fim .' '. $this->horario_fim);
    }

}
