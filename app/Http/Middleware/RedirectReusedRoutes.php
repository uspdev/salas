<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Log;

class RedirectReusedRoutes
{
    public function handle(Request $request, Closure $next)
    {
        // neste método, realizamos redirecionamentos de itens de menu que utilizam rotas já utilizadas por outros itens
        // nada disso se fez necessário em outros sistemas do USPdev, pelo fato deles não terem itens de menu que compartilham as mesmas rotas
        // esta codificação aqui (mais outras codificações nos controllers) permite que possamos fazer highlight nos itens de menu corretos, naqueles que o usuário clicou, mesmo tendo rotas compartilhadas com outros itens

        // ao executarmos os app()->handle abaixo, este handle aqui do RedirectReusedRoutes acaba sendo invocado uma segunda vez... isso cria o problema de cair no else final e indevidamente apagar session('origem')
        // então verificamos se se trata dessa segunda execução e, em caso afirmativo, simplesmente seguimos com o fluxo, sem apagar session('origem')
        if ($request->headers->has('X-Internal-Request'))
            return $next($request);

        // ao invés de fazermos return redirect()->route(...), fazemos app->handle(...)
        // com o app->handle(), mantemos a URL original na barra de navegação, fazendo com que os highlights do menu continuem funcionando corretamente mesmo quando o usuário tecla F5
        // o único porém do app->handle() é que ele perde a URL base; para corrigir isso, nós a setamos no middleware ConfigureBaseUrl

        if ($request->is('filtro_de_recursos')) {                        // se forem rotas não definidas no routes/web.php, ou seja, itens de menu que usam links já utilizados por outros itens...
            session(['origem' => 'filtro_de_recursos']);                 // defino session('origem'), para ser capaz de identificar a origem no \UspTheme::activeUrl nos controllers...
            $new_request = Request::create('salas/listar', 'GET', $request->query());
            $new_request->headers->set('X-Internal-Request', 'true');    // seto um header para posteriormente conseguir identificar que se trata de uma requisição interna
            return app()->handle($new_request);                          // e faço um app->handle(...) para a rota correta

        } else {                                                         // se forem rotas já definidas no routes/web.php...
            session()->forget('origem');                                 // simplesmente apago session('origem')...
            return $next($request);                                      // e deixo o Laravel seguir seu fluxo normal
        }
    }
}
