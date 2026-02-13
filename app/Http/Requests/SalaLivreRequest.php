<?php

namespace App\Http\Requests;

use Carbon\Carbon;
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
            'recursos' => 'nullable',
            'data_limite' => ['bail','nullable','date_format:d/m/Y','after:data','required_with:repeat_days',
            function($data, $value, $fail){
                $data = Carbon::createFromFormat('d/m/Y',$this->input('data'));
                $limite = Carbon::createFromFormat('d/m/Y',$value   );
                if($limite->lt($data->addDays(7))){
                    $fail('A data limite precisa ser pelo menos 7 dias após a data inicial.');
                }
            },
        ],
            'repeat_days' => 'nullable|required_with:data_limite|between:0,7',
            'orWhere' => 'nullable',
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
            'data_limite.after' => 'Data de repetição deve ser maior do que a data de escolha inicial',
            'data_limite.required_with' => 'Insira a data limite de reserva',
            'data_limite.after_or_equal' => 'Insira uma data com 7 dias de diferença da data inicial',
            'repeat_days.required_with' => 'Insira os dias da semana',
        ];
    }
}
