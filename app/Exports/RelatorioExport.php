<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;

class RelatorioExport implements FromArray, WithHeadings
{
    protected $data;
    protected $headings;
    public function __construct($data, $headings){
        $this->data = $data;
        $this->headings = $headings;
    }

    public function array(): array
    {
        foreach($this->data as $dia => $data){
            $array[$dia] = array_merge([$dia],$data->toarray());
        }
        return $array;
    }

    public function headings() : array
    {
        return $this->headings;
    }
}
