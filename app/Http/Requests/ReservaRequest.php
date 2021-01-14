<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Carbon\Carbon;
use App\Rules\verifyRoomAvailability;

class ReservaRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        /**
         * A validação da disponibilidade será customizada
         */
        $data = new verifyRoomAvailability([
            'horario_inicio' => $this->horario_inicio,
            'horario_fim'    => $this->horario_fim,
            'sala_id'        => $this->sala_id
        ]);

        return [
            'nome'           => 'required',
            'horario_inicio' => 'required|date_format:H:i',
            'horario_fim'    => 'required|date_format:H:i|after:horario_inicio',
            'cor'            => 'nullable',
            'sala_id'        => 'required',
            'descricao'      => 'nullable',
            'data'           => ['required','date_format:d/m/Y',$data],
        ];
    }

    public function messages()
    {
        return [
            'nome.required'           => 'O nome não pode ficar em branco.',
            'data.required'           => 'A data não pode ficar em branco.',
            'horario_inicio.required' => 'O horário de início não pode ficar em branco.',
            'horario_fim.required'    => 'O horário de fim não pode ficar em branco.',
            'sala_id.required'        => 'Selecione uma sala.',
            'full_day_event.required' => 'Selecione uma opção para "Evento de dia inteiro".',
        ];
    }




}
