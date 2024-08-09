<?php

namespace App\Http\Requests;

use App\Models\Sala;
use App\Rules\verifyRoomAvailability;
use Illuminate\Support\Facades\Gate;
use App\Rules\RestricoesSalaRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Carbon\Carbon;

class ReservaRequest extends FormRequest
{
    /**
     * Indicates if the validator should stop on the first rule failure.
     *
     * @var bool
     */
    protected $stopOnFirstFailure = true;

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

        /*
         * A validação da disponibilidade será customizada
         */
        if ($this->method() == 'PATCH' || $this->method() == 'PUT') {
            $id = $this->reserva->id;
        } else {
            $id = 0;
        }


        $rules = [
            'nome' => 'required',
            'horario_inicio' => 'required|date_format:G:i|',
            'horario_fim' => 'required|date_format:G:i|after:horario_inicio|',
            'finalidade_id' => 'required|integer',
            'descricao' => 'nullable',
            'repeat_until' => ['required_with:repeat_days', 'nullable', 'date_format:d/m/Y'],
            'repeat_days.*' => 'integer|between:0,7',
            'data' => ['bail', 'required', 'date_format:d/m/Y', new verifyRoomAvailability($this, $id)],
            'sala_id' => ['required', Rule::in(Sala::pluck('id')->toArray()), new RestricoesSalaRule($this) ],
            'tipo_responsaveis' => 'required'
        ];

        $sala = Sala::find($this->sala_id); 
        if(!is_null($sala) && !Gate::allows('responsavel', $sala)){
            array_push($rules['data'], 'after_or_equal:today');
            $date_today = Carbon::createFromFormat('d/m/Y', date('d/m/Y'));
            $date_input = Carbon::createFromFormat('d/m/Y', $this->data);
            if($date_input->eq($date_today)) {
                $rules['horario_inicio'] .= 'after:'. date('G:i');
            }
        }

        return $rules;
    }

    public function messages()
    {
        return [
            'nome.required' => 'O título não pode ficar em branco.',
            'data.required' => 'A data não pode ficar em branco.',
            'data.date_format' => 'A data deve ser válida e inserida no formato dia/mês/ano.',
            'horario_inicio.required' => 'O horário de início não pode ficar em branco.',
            'horario_fim.required' => 'O horário de fim não pode ficar em branco.',
            'horario_inicio.date_format' => 'Digite o horário no formato 0:00. Exemplo: 9:00',
            'horario_fim.date_format' => 'Digite o horário no formato 0:00. Exemplo: 9:00',
            'sala_id.required' => 'Selecione uma sala.',
            'repeat_until.required_with' => 'Selecione uma data para o fim da repetição.',
            'repeat_until.date_format' => 'A data de repetição deve ser válida e inserida no formato dia/mês/ano.',
            'data.after_or_equal' => 'Não é possível fazer reservas em dias passados.',
            'horario_inicio.after' => 'Não é possível fazer reservas em um horário passado.',
        ];
    }
}
