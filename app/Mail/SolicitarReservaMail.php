<?php

namespace App\Mail;

use App\Models\Reserva;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SolicitarReservaMail extends Mailable
{
    use Queueable, SerializesModels;
    private $reserva;
    private $signed_urls;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Reserva $reserva, $signed_urls = null)
    {
        $this->reserva = $reserva;
        $this->signed_urls = $signed_urls;
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
                    ->subject('Novo pedido de reserva solicitado - Sistema Reserva de Salas')
                    ->with([
                        'reserva' => $this->reserva,
                        'signed_urls' => $this->signed_urls,
                    ]);
    }
}
