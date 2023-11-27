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
                        && env('MAIL_PASSWORD') != null && env('MAIL_ENCRYPTION') != null)
];