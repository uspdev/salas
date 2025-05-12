<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\Reserva;
use App\Models\User;
use App\Models\Sala;

class CreateReservaAoResponsavelMail extends Mailable
{
    use Queueable, SerializesModels;
    private $reserva;
    private $responsavel;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Reserva $reserva, User $responsavel)
    {
        $this->reserva = $reserva;
        $this->responsavel = $responsavel;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.create_reservaaoresponsavel')
                    ->subject('Reserva de sala aprovada automaticamente — Sistema Reserva de Salas')
                    ->to($this->responsavel->email)
                    ->with([
                        'reserva' => $this->reserva,
                        'data' => $this->reserva->getRawOriginal('data'),
                        'signed_urls' => null
                    ]);
    }
}
