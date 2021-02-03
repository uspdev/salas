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
        if($value) return Carbon::CreateFromFormat('Y-m-d', $value)->format('d/m/Y');
    }

    public function setRepeatUntilAttribute($value) {
        if($value) $this->attributes['repeat_until'] = Carbon::createFromFormat('d/m/Y', $value);
    }

    public function getRepeatUntilAttribute($value) {
        if($value) return Carbon::CreateFromFormat('Y-m-d', $value)->format('d/m/Y');
    }

    public function setRepeatDaysAttribute($value) {
        $this->attributes['repeat_days'] = implode(',',$value);
    }

    public function getRepeatDaysAttribute($value) {
        if($value) return explode(',',$value);
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

    public function parent()
    {
        return $this->belongsTo($this,'parent_id');
    }

    public function children()
    {
        return $this->hasMany($this, 'parent_id');
    }

    public function irmaos()
    {
        if($this->parent_id != null)
            return Reserva::where('parent_id',$this->parent_id)->get();
        return ;
    }

}
