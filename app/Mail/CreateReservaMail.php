<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\Reserva;
use App\Models\User;
use App\Models\Sala;

class CreateReservaMail extends Mailable
{
    use Queueable, SerializesModels;
    private $reserva;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Reserva $reserva)
    {
        $this->reserva = $reserva;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $user = User::find($this->reserva->user_id);
        return $this->view('emails.create_reserva')
                    ->subject('Novo cadastro de reserva de sala — Sistema Reserva de Salas')
                    ->to($user->email)
                    ->with([
                        'reserva' => $this->reserva,
                        'data' => $this->reserva->getRawOriginal('data'),
                    ]);
    }
}
