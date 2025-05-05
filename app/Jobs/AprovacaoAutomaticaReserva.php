<?php

namespace App\Jobs;

use App\Mail\CreateReservaMail;
use App\Models\Reserva;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class AprovacaoAutomaticaReserva implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $reserva_id;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($reserva_id)
    {
        $this->reserva_id = $reserva_id;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $reserva = Reserva::where('id', $this->reserva_id)->first();
        if ($reserva->parent_id != null) {
            // aprova todas as recorrências da reserva
            Reserva::where('parent_id', $reserva->parent_id)->get()->map(function ($res) {
                $res->status = 'aprovada';
                $res->save();
            });
        } else {
            $reserva->status = 'aprovada';
            $reserva->save();
        }

        // envia e-mail ao ser aprovada
        if (config('salas.emailConfigurado'))
            Mail::queue(new CreateReservaMail($reserva));
    }
}
