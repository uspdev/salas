<?php

namespace App\Http\Requests;

use Carbon\Carbon;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CalendarioRequest extends FormRequest
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
        $categorias = \App\Models\Categoria::pluck('id')->toarray();
        
        return [
            'data' => ['nullable','date_format:d/m/Y'],
            'categoria_id' => ['nullable'],
        ];
    }

    public function messages(){
        return [
            'data.date_format' => 'Formato de data inválido',
        ];
    }
}
