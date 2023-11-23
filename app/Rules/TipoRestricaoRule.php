<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class TipoRestricaoRule implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */

    private $data_limite;
    private $dias_limite;
    private $periodo_letivo;
    private $message; 

    public function __construct($data_limite, $dias_limite, $periodo_letivo)
    {
        $this->data_limite = $data_limite; 
        $this->dias_limite = $dias_limite;
        $this->periodo_letivo = $periodo_letivo;

    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        
        $tipo_restricao = $value;

        if ($tipo_restricao == 'NENHUMA'){
            return true;
        }


        if ($tipo_restricao == 'AUTO') {

            if($this->dias_limite == NULL) {
                $this->message = "A data limite dinâmica precisa ser informada";
                return false;
            } else {
                return true;
            }

        }


        if ($tipo_restricao == 'FIXA') {
            if($this->data_limite == NULL) {
                $this->message = "A data limite fixa precisa ser informada";
                return false;
            } else {
                return true;
            }

        }

        if ($tipo_restricao == 'PERIODO_LETIVO') {
            if($this->periodo_letivo == NULL) {
                $this->message = "Um período letivo precisa ser escolhido";
                return false;
            } else {
                return true;
            }
        }

    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return $this->message;
    }
}
