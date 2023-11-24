<?php

namespace App\Utils;

use Uspdev\Replicado\DB;

class ReplicadoUtils
{

    /**
     * Método que recebe o código da unidade e retorna todos os campos da tabela UNIDADE referente ao código de unidade passado.
     * Se $fields também for passado busca apenas os campos solicitados neste.
     *
     * @param Integer $codundclg
     * @param array $fields
     * @return Mixed
     */
    public static function dumpUnidade($codundclg, $fields = ['*']){
        $columns = implode(',', $fields);
        $query = "SELECT {$columns} FROM UNIDADE u WHERE u.codund IN ($codundclg)";

        return DB::fetchAll($query);
    }

}
