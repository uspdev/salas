<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use App\Models\Reserva;
use Carbon\Carbon;

class verifyRoomAvailability implements Rule
{
    private $fields;
    private $conflicts = '';
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct(array $fields)
    {
        $this->fields = $fields;
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
        # 1. Vamos pegar as reservas que existem para o mesmo dia e mesmo horário
        $data = Carbon::createFromFormat('d/m/Y', $value);
        $reservas = Reserva::whereDate('data','=',$data)->where('sala_id',$this->fields['sala_id'])->get();

        # 2. Se não há reserva alguma na data e sala em questão, podemos cadastrar
        if($reservas->isEmpty()) return true;

        # 3. Vamos construir $inicio e $fim
        $inicio = Carbon::createFromFormat('d/m/Y H:i', $value . ' ' .$this->fields['horario_inicio']);
        $fim    = Carbon::createFromFormat('d/m/Y H:i', $value . ' ' .$this->fields['horario_fim']);

        #foreach($reservas as $reserva){
        #    dd($reserva->nome);
        #}

        # 4. Se há conflitos vamos montar a string $conflicts indicando-os
        foreach($reservas as $reserva){
            $this->conflicts .= "<li><a href='/reservas/{$reserva->id}'>$reserva->nome</a></li>";
        }
        return false;
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
}
