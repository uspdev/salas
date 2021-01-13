<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

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
        ];
    }

    public function messages()
    {
    return [
        'nome.required'         => 'O nome não pode ficar em branco.',
        'categoria_id.required' => 'A categoria não pode ficar em branco.',
        'capacidade.required'   => 'A capacidade não pode ficar em branco.',
        'capacidade.integer'   => 'A capacidade deve ser um número.',
    ];
    }
}
