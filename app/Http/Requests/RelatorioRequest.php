<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\Models\Categoria;

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

        $categorias = Categoria::pluck('id')->toArray();

        return [
            'inicio' => 'required|date_format:d/m/Y',
            'fim' => 'required|date_format:d/m/Y',
            'categoria_id' => ['required', Rule::in($categorias)]
        ];
    }

    public function messages(){
        return [
            'inicio.required' => 'A data inicial é obrigatória',
            'fim.required' => 'A data final é obrigatória',
            'inicio.date_format' => 'Insira a data no formato d/m/y',
            'fim.date_format' => 'Insira a data no formato d/m/y',
            'categoria_id' => 'A categoria é obrigatória'
        ];
    }

}
