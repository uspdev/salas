<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\Reserva;
use App\Models\User;
use App\Models\Sala;
use App\Models\Finalidade;
use Carbon\Carbon;

class DeleteReservaMail extends Mailable
{
    use Queueable;    // SerializesModels provocava erro de reserva já deletada e tendo acessá-la
    private $reserva;
    private $purge;
    private $justificativa;
    private $changedBy;
    private $oldValues;
    private $changedAt;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Reserva $reserva, bool $purge = false, string $justificativa = null, ?array $changedBy = null, array $oldValues = [], ?Carbon $changedAt = null)
    {
        $this->reserva = $reserva;
        $this->purge = $purge;
        $this->justificativa = $justificativa;
        $this->changedBy = $changedBy;
        $this->oldValues = $oldValues;
        $this->changedAt = $changedAt ?? now();
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $user = User::find($this->reserva->user_id);
        $recipients = collect([$user?->email, $this->changedBy['email'] ?? null])
            ->filter()
            ->unique()
            ->values()
            ->all();

        return $this->view('emails.delete_reserva')
                    ->subject('Exclusão de reserva — Sistema Reserva de Salas')
                    ->to($recipients)
                    ->with([
                        'reserva' => $this->reserva,
                        'purge' => $this->purge,
                        'justificativa' => $this->justificativa,
                        'changed_by' => $this->changedBy,
                        'changed_at' => $this->changedAt,
                        'changes' => $this->changes(),
                    ]);
    }

    private function changes(): array
    {
        $labels = [
            'nome' => 'Título', 'data' => 'Data', 'horario_inicio' => 'Horário inicial',
            'horario_fim' => 'Horário final', 'sala_id' => 'Sala', 'finalidade_id' => 'Finalidade',
            'descricao' => 'Descrição', 'repeat_until' => 'Repetir até', 'repeat_days' => 'Dias de repetição',
            'extras' => 'Informações adicionais',
            'status' => 'Status', 'cor' => 'Cor', 'usptrack' => 'Rastreamento USP',
        ];
        $changes = [];

        foreach ($labels as $key => $label) {
            if (!array_key_exists($key, $this->oldValues)) continue;
            $changes[] = [
                'label' => $label,
                'old' => $this->displayValue($key, $this->oldValues[$key]),
                'new' => 'Excluído',
            ];
        }

        return $changes;
    }

    private function displayValue(string $key, mixed $value): string
    {
        if ($value === null || $value === '') return 'Não informado';
        if ($key === 'sala_id') return Sala::find($value)?->nome ?? (string) $value;
        if ($key === 'finalidade_id') return Finalidade::find($value)?->legenda ?? (string) $value;
        if (in_array($key, ['data', 'repeat_until'], true)) return Carbon::parse($value)->format('d/m/Y');
        if ($key === 'repeat_days' && is_array($value)) return implode(', ', $value);
        if ($key === 'extras' && is_string($value)) return json_encode(json_decode($value, true), JSON_UNESCAPED_UNICODE);

        return (string) $value;
    }
}
