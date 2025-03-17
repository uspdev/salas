<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\URL;

class ConfigureBaseUrl
{
    public function handle($request, Closure $next)
    {
        URL::forceRootUrl(config('laravel-usp-theme.app_url'));    // define a URL base do site para o domínio original... isso se fez necessário a partir do momento que começamos a utilizar os comandos app()->handle() no middleware RedirectReusedRoutes

        return $next($request);
    }
}
