<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RelatorioRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'inicio' => 'required|date_format:d/m/Y',
            'fim' => 'required|date_format:d/m/Y'
        ];
    }

    public function messages(){
        return [
            'inicio.required' => 'A data inicial obrigatória',
            'fim.required' => 'A data final é obrigatória',
            'inicio.date_format' => 'Insira a data no formato d/m/y',
            'fim.date_format' => 'Insira a data no formato d/m/y',
        ];
    }

}
