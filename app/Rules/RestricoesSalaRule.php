<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use App\Models\Sala;
use App\Models\Restricao;
use App\Models\PeriodoLetivo;

use Carbon\Carbon;


class RestricoesSalaRule implements Rule
{


    private $reserva;
    private $message;
    private $validationErrors = 0;



    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct($reserva)
    {
        $this->reserva = $reserva;
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

        $sala = Sala::with('restricao')->find($value);

        /* sala bloqueada */
        if ($sala->restricao->bloqueada == TRUE) {
            $this->message .= "A sala $sala->nome está bloqueada para reservas. " . $sala->restricao->motivo_bloqueio . "<br>";
            $this->validationErrors++;
        }


        /* respeita a antecedência mínima */
        if ($sala->restricao->dias_antecedencia > (Carbon::now()->diffInDays(Carbon::createFromFormat('d/m/Y', $this->reserva->data)->format('Y-m-d'), false))) {
            $this->message .= "As reservas na sala $sala->nome precisam ser solicitadas com até " . $sala->restricao->dias_antecedencia . " dias de antecedência<br>";
            $this->validationErrors++;
        }


        /* verificar se a data da reserva é antes dos dia limite dinamicamente calculado */
        if ($sala->restricao->tipo_restricao === 'AUTO') {

            $dataReserva = Carbon::createFromFormat('d/m/Y', $this->reserva->data);
            $dataLimite = Carbon::now()->addDays($sala->restricao->dias_limite);

            if ($dataReserva->isAfter($dataLimite)) {
                $this->message .= "A sala $sala->nome aceita reservas somente até o dia " . Carbon::parse($dataLimite)->format('d/m/Y') . "<br>";
                $this->validationErrors++;
            }
        }

        /* verificar se a data da reserva é antes da data limite estabelecida */
        if ($sala->restricao->tipo_restricao === 'FIXA') {

            $dataReserva = Carbon::createFromFormat('d/m/Y', $this->reserva->data);

            if ($dataReserva->isAfter($sala->restricao->data_limite)) {
                $this->message .= "A sala $sala->nome aceita reservas somente até o dia " . Carbon::parse($sala->restricao->data_limite)->format('d/m/Y') . "<br>";
                $this->validationErrors++;
            }
        }

        /* verificar se a data da reserva está dentro dos limites do período letivo */
        if ($sala->restricao->tipo_restricao === 'PERIODO_LETIVO') {

            $periodo = PeriodoLetivo::find($sala->restricao->periodo_letivo_id);

            $dataReserva = Carbon::createFromFormat('d/m/Y', $this->reserva->data);

            if (!$dataReserva->between($periodo->data_inicio_reservas, $periodo->data_fim_reservas)) {
                $this->message .= "A sala $sala->nome aceita reservas somente entre os dias " . Carbon::parse($periodo->data_inicio_reservas)->format('d/m/Y') . " e " . Carbon::parse($periodo->data_fim_reservas)->format('d/m/Y') . "<br>";
                $this->validationErrors++;
            }
        }


        /* verificar se a reserva atende as restrições de duração mínima */
        if ($sala->restricao->duracao_minima > 0) {
            $hi = Carbon::createFromFormat('H:i', $this->reserva->horario_inicio);
            $hf = Carbon::createFromFormat('H:i', $this->reserva->horario_fim);
            $duracao = $hi->diffInMinutes($hf) ;

            if ($duracao < $sala->restricao->duracao_minima) {
                $this->message .= "A reserva não tem a duração mínima definida para a sala $sala->nome que é de ". $sala->restricao->duracao_minima . " minutos" . "<br>";
                $this->validationErrors++;
            }
        }

        /* verificar se a reserva atende as restrições de duração máxima  */
        if ($sala->restricao->duracao_maxima > 0) {
            $hi = Carbon::createFromFormat('H:i', $this->reserva->horario_inicio);
            $hf = Carbon::createFromFormat('H:i', $this->reserva->horario_fim);
            $duracao = $hi->diffInMinutes($hf) ;

            if ($duracao > $sala->restricao->duracao_maxima) {
                $this->message .= "A reserva supera a duração máxima definida para a sala $sala->nome que é de ". $sala->restricao->duracao_maxima . " minutos <br>";
                $this->validationErrors++;
            }
        }


        if ($this->validationErrors > 0) {
            return false;
        } else {
            return true;
        }
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return $this->message;
    }
}
