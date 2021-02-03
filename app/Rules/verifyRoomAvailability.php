<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use App\Models\Reserva;
use Carbon\Carbon;
use Carbon\CarbonPeriod;

class verifyRoomAvailability implements Rule
{
    private $reserva;
    private $id;
    private $conflicts = '';
    private $n = 0;
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct($reserva, $id)
    {
        $this->reserva = $reserva;
        $this->id = $id;
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        $this->check($value);

        if($this->reserva->repeat_days){
            # TODO: temos que checar que $fim é maior que $inicio
            $inicio = Carbon::createFromFormat('d/m/Y', $value);
            $fim = Carbon::createFromFormat('d/m/Y', $this->reserva->repeat_until);
            $period = CarbonPeriod::between($inicio, $fim);

            foreach ($period as $date) {
                if(in_array($date->dayOfWeek, $this->reserva->repeat_days)){
                    $this->check($date->format('d/m/Y'));
                }
            }
        }

        if($this->n != 0) return false; 
        return true;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return "Reserva não foi criada porque conflita com: <ul>{$this->conflicts}</ul>";
    }

    private function check($day){
        # 1. Vamos pegar as reservas que existem para o mesmo dia e mesmo horário
        $data = Carbon::createFromFormat('d/m/Y', $day);
        $reservas = Reserva::whereDate('data','=',$data)->where('sala_id',$this->reserva->sala_id)->get();

        # 2. Se não há reserva alguma na data e sala em questão, podemos cadastrar
        if($reservas->isEmpty()) return true;

        # 3. Se há conflitos vamos montar a string $conflicts indicando-os
        $inicio = Carbon::createFromFormat('d/m/Y H:i', $day . ' ' .$this->reserva->horario_inicio);
        $fim    = Carbon::createFromFormat('d/m/Y H:i', $day . ' ' .$this->reserva->horario_fim);

        $desejado = CarbonPeriod::between($inicio, $fim);

        foreach($reservas as $reserva){
            $period = CarbonPeriod::between($reserva->inicio, $reserva->fim);
            if($period->overlaps($desejado)) {
                # vamos ignorar a própria reserva
                if($this->id != $reserva->id){
                    $this->conflicts .= "<li><a href='/reservas/{$reserva->id}'>$reserva->nome</a></li>";
                    $this->n++;
                }
            }
        }
    }
}
