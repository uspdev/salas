<?php

namespace App\Providers;

use Illuminate\Support\Facades\Event;
use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Paginator::useBootstrap();
        if (\App::environment('production')) {
          \URL::forceScheme('https');
        }

        if (config('mail.copiarRemetente'))
            // faz com que todo e qualquer e-mail enviado para os diversos atores seja copiado para o e-mail de envio do sistema
            // desta forma, na caixa de entrada do e-mail de envio do sistema, teremos um histórico de todos os e-mails enviados
            Event::listen(MessageSending::class, function (MessageSending $event) {
                $event->message->addBcc(config('mail.from.address'));
            });
    }
}
