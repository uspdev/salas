<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SalaLivreRequest extends FormRequest
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
            'data' => 'required|date_format:d/m/Y',
            'horario_inicio' => 'required|date_format:G:i|',
            'horario_fim' => 'required|date_format:G:i|after:horario_inicio',
        ];
    }

    public function messages()
    {
        return [
            'data.required' => 'A data não pode ficar em branco.',
            'data.date_format' => 'A data deve ser válida e inserida no formato dia/mês/ano.',
            'horario_inicio.required' => 'O horário de início não pode ficar em branco.',
            'horario_fim.required' => 'O horário de fim não pode ficar em branco.',
            'horario_inicio.date_format' => 'Digite o horário de início no formato 0:00. Exemplo: 9:00',
            'horario_fim.date_format' => 'Digite o horário fim no formato 0:00. Exemplo: 9:00',
            'horario_fim.after' => 'Horário fim precisa ser maior que o horário de início.',
        ];
    }
}
