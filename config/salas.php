<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Arquivo padrão de configuração do salas
    |--------------------------------------------------------------------------
    */
    'codUnidade' => env('REPLICADO_CODUNDCLG', 8),
    'cores' => [
        'pendente' => '#FFB540',
        'semFinalidade' => '#BDBDBD'
    ],
    'emailConfigurado' => (env('MAIL_MAILER') != null   && env('MAIL_HOST') != null
                        && env('MAIL_PORT') != null     && env('MAIL_USERNAME') != null
                        && env('MAIL_PASSWORD') != null && env('MAIL_ENCRYPTION') != null),

    // campos de preenchimento obrigatório quando de solicitação de reserva
    'reservaCamposExtras' => env('RESERVA_CAMPOS_EXTRAS') ? explode(',', env('RESERVA_CAMPOS_EXTRAS')) : null,

    // configurações do calendário de sala
    'calendarioHoraInicial' => env('CALENDARIO_HORA_INICIAL') ?: '00:00',
    'calendarioHoraFinal'   => env('CALENDARIO_HORA_FINAL'  ) ?: '24:00',
];
