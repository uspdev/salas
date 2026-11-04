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

class UpdateReservaMail extends Mailable
{
    use Queueable, SerializesModels;
    private $reserva;
    private $changedBy;
    private $oldValues;
    private $newValues;
    private $changedAt;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Reserva $reserva, ?array $changedBy = null, array $oldValues = [], array $newValues = [], ?Carbon $changedAt = null)
    {
        $this->reserva = $reserva;
        $this->changedBy = $changedBy;
        $this->oldValues = $oldValues;
        $this->newValues = $newValues;
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

        return $this->view('emails.update_reserva')
                    ->subject('Reserva modificada - Sistema Reserva de Salas')
                    ->to($recipients)
                    ->with([
                        'reserva' => $this->reserva,
                        'data' => $this->reserva->getRawOriginal('data'),
                        'changed_by' => $this->changedBy,
                        'changed_at' => $this->changedAt,
                        'changes' => $this->changes(),
                    ]);
    }

    private function changes(): array
    {
        $labels = [
            'nome' => 'Título',
            'data' => 'Data',
            'horario_inicio' => 'Horário inicial',
            'horario_fim' => 'Horário final',
            'sala_id' => 'Sala',
            'finalidade_id' => 'Finalidade',
            'descricao' => 'Descrição',
            'repeat_until' => 'Repetir até',
            'repeat_days' => 'Dias de repetição',
            'extras' => 'Informações adicionais',
            'status' => 'Status',
            'cor' => 'Cor',
            'usptrack' => 'Rastreamento USP',
        ];
        $changes = [];

        foreach (array_unique(array_merge(array_keys($this->oldValues), array_keys($this->newValues))) as $key) {
            if (!isset($labels[$key])) continue;

            $old = $this->oldValues[$key] ?? null;
            $new = $this->newValues[$key] ?? null;
            if ((string) $old === (string) $new) continue;

            $changes[] = [
                'label' => $labels[$key],
                'old' => $this->displayValue($key, $old),
                'new' => $this->displayValue($key, $new),
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
