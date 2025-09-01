<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Recurso;
use App\Models\Reserva;
use App\Models\Categoria;
use App\Models\Restricao;
use OwenIt\Auditing\Contracts\Auditable;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class Sala extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;

    use HasFactory;
    protected $guarded = ['id'];

    public function reservas()
    {
        return $this->hasMany(Reserva::class);
    }

    public function recursos()
    {
        return $this->belongsToMany(Recurso::class)
                    ->withTimestamps();
    }

    public function categoria()
    {
        return $this->belongsTo(Categoria::class);
    }

    public function responsaveis()
    {
        return $this->belongsToMany(User::class, 'responsaveis')->withPivot('id');
    }

    public function restricao()
    {
        return $this->hasOne(Restricao::class);
    }

    public static function SalasLivresQuery(array $validated) {
        $recursos = $validated['recursos'] ?? [];
        $horario_inicio = $validated['horario_inicio'];
        $horario_fim = $validated['horario_fim'];
        $qtd_recursos = count($recursos);
        $placeholders = $qtd_recursos > 0 ? implode(',', array_fill(0, $qtd_recursos, '?')) : '';
        /* a variável $placeholders repete o caracter "?" conforme a qtd de recursos */
        if($validated['data'] && $validated['data_limite']){
            $periodo = \Carbon\CarbonPeriod::between(
                Carbon::createFromFormat('d/m/Y',$validated['data']), 
                Carbon::createFromFormat('d/m/Y',$validated['data_limite'])
            );
            foreach($periodo as $dia){
                if(in_array($dia->dayOfWeek,$validated['repeat_days'])){
                    $dias_repetidos[] = $dia->toDateString();
                }
            }
            //verificar para pegar no mínimo de 1 semana. diferença de menos de 3 dias dá erro
            $qtd_dias = count($dias_repetidos);
            $dias_do_mes = implode(',',array_fill(0, $qtd_dias, '?'));
        }
        
        $data = Carbon::createFromFormat('d/m/Y', $validated['data'])->format('Y-m-d');
        $query = "SELECT s.id, s.capacidade, s.nome, c.nome AS nomcat
            FROM salas s
            LEFT JOIN reservas r ON r.sala_id = s.id
            LEFT JOIN restricoes x ON x.sala_id = s.id
            LEFT JOIN recurso_sala rs on rs.sala_id = s.id
            INNER JOIN categorias c ON c.id = s.categoria_id
            WHERE s.id NOT IN (
                SELECT r.sala_id
                FROM reservas r
                WHERE
                    (
                        r.horario_inicio < STR_TO_DATE(?, '%H:%i')
                        AND r.horario_fim > STR_TO_DATE(?, '%H:%i')
                        AND r.data = ?
                    )
                    OR (
                        x.bloqueada = 1
                    ) #verificação de dia semanal
                        ". (!empty($validated['data_limite']) ? 
                    "OR r.data IN ($dias_do_mes) 
                    AND r.horario_inicio < STR_TO_DATE(?, '%H:%i') 
                    AND horario_fim > STR_TO_DATE(?, '%H:%i')" 
                        : "")  ."
                )
            "
            . ($qtd_recursos > 0 ? " AND rs.recurso_id IN ($placeholders)": ""). "
            GROUP BY s.id, s.capacidade, s.nome, c.nome
            ". ($qtd_recursos > 0 ? "HAVING COUNT(DISTINCT rs.recurso_id) = ?" : "")
            ;

            $params = [
                $horario_fim, $horario_inicio, $data,
            ];

        if(!empty($validated['data_limite']) && !empty($dias_repetidos)){
            $params = array_merge($params, $dias_repetidos, [$horario_fim, $horario_inicio]);
        }

        if ($qtd_recursos > 0){
            $params = array_merge($params, $recursos, [$qtd_recursos]);
        }

        $resultado = collect(DB::select($query, $params))->groupBy('nomcat');
        
        return $resultado;
    }

}
