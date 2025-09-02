<?php

namespace App\Models;

use App\Actions\SalasReservadas;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Recurso;
use App\Models\Reserva;
use App\Models\Categoria;
use App\Models\Restricao;
use OwenIt\Auditing\Contracts\Auditable;
use Illuminate\Support\Facades\DB;

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
        $qtd_recursos = count($recursos);
        $placeholders = $qtd_recursos > 0 ? implode(',', array_fill(0, $qtd_recursos, '?')) : '';
        /* a variável $placeholders repete o caracter "?" conforme a qtd de recursos */

        $query = "SELECT s.id, s.capacidade, s.nome, c.nome AS nomcat
            FROM salas s
            LEFT JOIN reservas r ON r.sala_id = s.id
            LEFT JOIN restricoes x ON x.sala_id = s.id
            LEFT JOIN recurso_sala rs on rs.sala_id = s.id
            INNER JOIN categorias c ON c.id = s.categoria_id
            WHERE s.id NOT IN (".SalasReservadas::handle($validated).")"
            . ($qtd_recursos > 0 ? " AND rs.recurso_id IN ($placeholders)": ""). "
            GROUP BY s.id, s.capacidade, s.nome, c.nome
            ". ($qtd_recursos > 0 ? "HAVING COUNT(DISTINCT rs.recurso_id) = ?" : "")
            ;

        $params = [];

        if ($qtd_recursos > 0){
            $params = array_merge($params, $recursos, [$qtd_recursos]);
        }

        $resultado = collect(DB::select($query, $params))->groupBy('nomcat');
        
        return $resultado;
    }

}
