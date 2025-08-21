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
        $horario_inicio = $validated['horario_inicio'];
        $horario_fim = $validated['horario_fim'];
        $data = Carbon::createFromFormat('d/m/Y', $validated['data'])->format('Y-m-d');
        $query = "SELECT s.id, s.capacidade, s.nome, c.nome AS nomcat
            FROM salas s
            LEFT JOIN reservas r ON r.sala_id = s.id
            INNER JOIN categorias c ON c.id = s.categoria_id
            WHERE s.id NOT IN (
                SELECT r.sala_id
                FROM reservas r
                WHERE
                    (
                        r.horario_inicio = STR_TO_DATE(?, '%H:%i')
                        AND r.horario_fim = STR_TO_DATE(?, '%H:%i')
                        AND r.data = ?
                    )
                    OR (
                        r.horario_inicio > STR_TO_DATE(?, '%H:%i')
                        AND r.horario_fim < STR_TO_DATE(?, '%H:%i')
                        AND r.data = ?
                    )
                    OR (
                        r.horario_inicio < STR_TO_DATE(?, '%H:%i')
                        AND r.horario_fim > STR_TO_DATE(?, '%H:%i')
                        AND r.data = ?
                    )
                )
            GROUP BY s.id, s.capacidade, s.nome, c.nome
        ";

        return collect(DB::select($query,[
            $horario_inicio, $horario_fim, $data,
            $horario_inicio, $horario_fim, $data,
            $horario_fim, $horario_inicio, $data //sim, está certo
        ]))->groupBy('nomcat');
    }

}
