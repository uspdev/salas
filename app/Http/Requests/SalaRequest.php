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
            'nome'         => 'required',
            'categoria_id' => 'required',
            'capacidade'   => 'required|integer',
            'recursos'     => 'nullable',
            'aprovacao'    => 'required|integer',
            'bloqueada'    => 'nullable',
            'dias_antecedencia'   => 'nullable',
            'data_limite'   => 'nullable',
            'dias_limite'   => 'nullable',
            'periodo_letivo' => 'nullable',
            'tipo_restricao' => ['required', new TipoRestricaoRule($this->data_limite, $this->dias_limite, $this->periodo_letivo)],
        ];
    }

    public function messages()
    {
    return [
        'nome.required'         => 'O nome não pode ficar em branco.',
        'categoria_id.required' => 'A categoria não pode ficar em branco.',
        'capacidade.required'   => 'A capacidade não pode ficar em branco.',
        'capacidade.integer'    => 'A capacidade deve ser um número.',
    ];
    }
}
