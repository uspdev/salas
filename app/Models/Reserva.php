<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class Reserva extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;

    use HasFactory;
    protected $guarded = ['id'];

    public function setDataAttribute($value)
    {
        $this->attributes['data'] = Carbon::createFromFormat('d/m/Y', $value);
    }

    public function getDataAttribute($value)
    {
        if ($value) {
            return Carbon::CreateFromFormat('Y-m-d', $value)->format('d/m/Y');
        }
    }

    public function setRepeatUntilAttribute($value)
    {
        if ($value) {
            $this->attributes['repeat_until'] = Carbon::createFromFormat('d/m/Y', $value);
        }
    }

    public function getRepeatUntilAttribute($value)
    {
        if ($value) {
            return Carbon::CreateFromFormat('Y-m-d', $value)->format('d/m/Y');
        }
    }

    public function setRepeatDaysAttribute($value)
    {
        $this->attributes['repeat_days'] = implode(',', $value);
    }

    public function getRepeatDaysAttribute($value)
    {
        if ($value) {
            return explode(',', $value);
        }
    }

    /**
     * O mutator getInicioAttribute foi criado para usarmos no FullCalendar
     * a junção da data com o horário, desta forma: $reserva->inicio.
     */
    public function getInicioAttribute()
    {
        return Carbon::createFromFormat('d/m/Y H:i', $this->data.' '.$this->horario_inicio);
    }

    /**
     * O mutator getFimAttribute foi criado para usarmos no FullCalendar
     * a junção da data com o horário, desta forma: $reserva->fim.
     */
    public function getFimAttribute()
    {
        return Carbon::createFromFormat('d/m/Y H:i', $this->data.' '.$this->horario_fim);
    }

    public function parent()
    {
        return $this->belongsTo($this, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany($this, 'parent_id');
    }

    public function irmaos()
    {
        if ($this->parent_id != null) {
            return Reserva::where('parent_id', $this->parent_id)->get();
        }

        return;
    }

    public function sala()
    {
        return $this->belongsTo(Sala::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getIsParentAttribute()
    {
        return $this->parent_id === $this->id;
    }

    public function finalidade(){
        return $this->belongsTo(Finalidade::class);
    }
}
