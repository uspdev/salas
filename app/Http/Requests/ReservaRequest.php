<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Carbon\Carbon;

use App\Models\Sala;
use App\Rules\verifyRoomAvailability;
use Illuminate\Validation\Rule;

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
        if ($this->method() == 'PATCH' || $this->method() == 'PUT'){
            $id = $this->reserva->id;
        } else {
            $id = 0; 
        }

        $rules = [
            'nome'           => 'required',
            'horario_inicio' => 'required|date_format:H:i',
            'horario_fim'    => 'required|date_format:H:i|after:horario_inicio',
            'cor'            => 'nullable',
            'sala_id'        => ['required',Rule::in(Sala::pluck('id')->toArray())],
            'descricao'      => 'nullable',
            'data'           => ['required','date_format:d/m/Y',new verifyRoomAvailability($this,$id)],
            'repeat_until'   => ['required_with:repeat_days','nullable','date_format:d/m/Y'],
            'repeat_days.*'   => 'integer|between:0,6',
        ];

        return $rules;
    }

    public function messages()
    {
        return [
            'nome.required'           => 'O nome não pode ficar em branco.',
            'data.required'           => 'A data não pode ficar em branco.',
            'horario_inicio.required' => 'O horário de início não pode ficar em branco.',
            'horario_fim.required'    => 'O horário de fim não pode ficar em branco.',
            'sala_id.required'        => 'Selecione uma sala.',
        ];
    }




}
