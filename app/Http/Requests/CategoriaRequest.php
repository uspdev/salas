<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CategoriaRequest extends FormRequest
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
            'habilitar_pular_dias_conflito' => ['nullable', 'boolean'],
        ];
    }

    public function messages()
    {
        return [
            'nome.required' => 'O título não pode ficar em branco.',
            'habilitar_pular_dias_conflito.boolean' => 'A opção de pular dias de conflito deve ser válida.',
        ];
    }
}
