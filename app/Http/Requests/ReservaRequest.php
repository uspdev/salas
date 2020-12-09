<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

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
            'nome' => 'required',
            'data_inicio' => 'required',
            'data_fim' => 'required',
            'horario_inicio' => 'required',
            'horario_fim' => 'required',
            'cor' => 'nullable',
            'sala_id' => 'required',
        ];
    }

    public function messages()
    {
    return [
        'nome.required' => 'O nome não pode ficar em branco.',
        'data_inicio.required' => 'A data de início não pode ficar em branco.',
        'data_fim.required' => 'A data de fim não pode ficar em branco.',
    ];
    }


}
