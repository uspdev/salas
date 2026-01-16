<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Rules\TipoRestricaoRule;


class SalaRequest extends FormRequest
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
            'nome'                  => 'required',
            'categoria_id'          => 'required',
            'capacidade'            => 'required|integer',
            'recursos'              => 'nullable',
            'aprovacao'             => 'required|integer',
            'bloqueada'             => 'nullable',
            'motivo_bloqueio'       => 'nullable',
            'dias_antecedencia'     => 'nullable',
            'data_limite'           => 'nullable',
            'dias_limite'           => 'nullable',
            'periodo_letivo'        => 'nullable',
            'duracao_minima'        => 'nullable|numeric',
            'duracao_maxima'        => 'nullable|numeric',
            'tipo_restricao'        => ['required', new TipoRestricaoRule($this->data_limite, $this->dias_limite, $this->periodo_letivo)],
            'instrucoes_reserva'    => 'nullable',
            'aceite_reserva'        => 'nullable',
            'prazo_aprovacao'       => 'nullable|integer',
            'exige_justificativa_recusa' => 'required|integer',
        ];
    }

    public function messages()
    {
    return [
        'nome.required'         => 'O nome não pode ficar em branco.',
        'categoria_id.required' => 'A categoria não pode ficar em branco.',
        'capacidade.required'   => 'A capacidade não pode ficar em branco.',
        'capacidade.integer'    => 'A capacidade deve ser um número.',
        'prazo_aprovacao.integer' => 'O prazo de aprovação deve ser um número.',
    ];
    }
}
