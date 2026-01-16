<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\Reserva;
use App\Models\User;
use App\Models\Sala;

class DeleteReservaMail extends Mailable
{
    use Queueable;    // SerializesModels provocava erro de reserva já deletada e tendo acessá-la
    private $reserva;
    private $purge;
    private $justificativa;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Reserva $reserva, bool $purge = false, string $justificativa = null)
    {
        $this->reserva = $reserva;
        $this->purge = $purge;
        $this->justificativa = $justificativa;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $user = User::find($this->reserva->user_id);
        return $this->view('emails.delete_reserva')
                    ->subject('Exclusão de reserva — Sistema Reserva de Salas')
                    ->to($user->email)
                    ->with([
                        'reserva' => $this->reserva,
                        'purge' => $this->purge,
                        'justificativa' => $this->justificativa
                    ]);
    }
}
