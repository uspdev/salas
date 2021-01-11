<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Carbon\Carbon;

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
        return [
            'nome'           => 'required',
            'data_inicio'    => 'required|date_format:d/m/Y',
            'data_fim'       => 'required|date_format:d/m/Y|after_or_equal:data_inicio',
            'horario_inicio' => 'required|date_format:H:i:s',
            'horario_fim'    => 'required|date_format:H:i:s|after:horario_inicio',
            'cor'            => 'nullable',
            'sala_id'        => 'required',
            'full_day_event' => 'required',
            'descricao'      => 'nullable',
        ];
    }

    public function messages()
    {
        return [
            'nome.required'           => 'O nome não pode ficar em branco.',
            'data_inicio.required'    => 'A data de início não pode ficar em branco.',
            'data_fim.required'       => 'A data de fim não pode ficar em branco.',
            'horario_inicio.required' => 'O horário de início não pode ficar em branco.',
            'horario_fim.required'    => 'O horário de fim não pode ficar em branco.',
            'sala_id.required'        => 'Selecione uma sala.',
            'full_day_event.required' => 'Selecione uma opção para "Evento de dia inteiro".',
        ];
    }




}
