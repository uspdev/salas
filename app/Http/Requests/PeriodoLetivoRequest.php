<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;



class PeriodoLetivoRequest extends FormRequest
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
            'codigo'                => 'required',
            'data_inicio'           => 'required',
            'data_fim'              => 'required|date|after:data_inicio',
            'data_inicio_reservas'  => 'required',
            'data_fim_reservas'     => 'required|date|after:data_inicio_reservas',
        ];
    }

    public function messages()
    {
        return [
            'codigo.required'               => 'O nome não pode ficar em branco. Preencha no formato do JupiterWeb (ex: 20231).',
            'data_inicio.required'          => 'A data de início do período letivo não pode ficar em branco.',
            'data_fim.required'             => 'A data de término do período letivo não pode ficar em branco.',
            'data_inicio_reservas.required' => 'A data de início das reservas para o período letivo não pode ficar em branco.',
            'data_fim_reservas.required'    => 'A data de término das reservas para o período letivo não pode ficar em branco.',
            'data_fim.after'                => 'A data de término do período letivo deve ser após a data de início do período letivo.',
            'data_fim_reservas.after'       => 'A data de término das reservas para o período letivo deve ser após a data de início das reservas para o período letivo.',
        ];
    }
}
