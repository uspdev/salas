<?php

namespace App\Models;

use App\Jobs\AprovacaoAutomaticaReserva;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
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

    public function removerTarefa_AprovacaoAutomatica()
    {
        // remove jobs de aprovações automáticas de reserva
        foreach (DB::table('jobs')->where('payload->displayName', 'App\Jobs\AprovacaoAutomaticaReserva')->get() as $job) {
            $payload = json_decode($job->payload, true);
            if (!empty($payload['data']['command'])) {
                $command = unserialize($payload['data']['command']);                        // desserializa o comando
                $property = (new \ReflectionClass($command))->getProperty('reserva_id');    // usa ReflectionClass para acessar propriedades privadas
                $property->setAccessible(true);                                             // torna a propriedade acessível
                $reserva_id = $property->getValue($command);
                if ($reserva_id == $this->id)
                    DB::table('jobs')->where('id', $job->id)->delete();
            }
        }
    }

    public function reagendarTarefa_AprovacaoAutomatica()
    {
        // este método é utilizado tanto pela criação quanto alteração de reserva
        // quando o usuário altera uma reserva, eventualmente ele pode alterar a sala
        // neste caso, ao invés de alterarmos a job da reserva, simplesmente a removemos e a recriamos logo em seguida, considerando os dados eventualmente alterados

        $this->removerTarefa_AprovacaoAutomatica();

        if($this->sala->restricao != null && $this->sala->restricao->aprovacao && !empty($this->sala->restricao->prazo_aprovacao) && ($this->sala->restricao->prazo_aprovacao > 0)) {
            // (re)agenda job de aprovação automática de reserva
            $job_datahora = addWorkingDays($this->created_at, $this->sala->restricao->prazo_aprovacao);
            if ($job_datahora > now())
                AprovacaoAutomaticaReserva::dispatch($this->id)->delay($job_datahora);
        }
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

    public function responsaveis(){
        return $this->belongsToMany(ResponsavelReserva::class, 'reservas_responsaveis_reservas', 'reserva_id', 'responsavel_reserva_id')->withTimestamps();
    }
}
