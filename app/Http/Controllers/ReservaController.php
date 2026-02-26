<?php

namespace App\Http\Controllers;

use App\Http\Requests\ReservaRequest;
use App\Models\Reserva;
use App\Models\Sala;
use App\Models\Categoria;
use App\Service\GeneralSettings;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use App\Mail\CreateReservaMail;
use App\Mail\DeleteReservaMail;
use App\Mail\SolicitarReservaMail;
use App\Mail\UpdateReservaMail;
use App\Models\Finalidade;
use App\Models\ResponsavelReserva;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\URL;
use Uspdev\Replicado\Pessoa;
use App\Actions\GetPeriodoAction;
use App\Actions\CreateReservaAction;

class ReservaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function my()
    {
        $this->authorize('logado');
        $reservas = Reserva::where('user_id', auth()->user()->id);

        // Só estamos interessados nas reservas de maior hierarquia
        $reservas->where(function ($query) {
            $query->whereNull('parent_id')->orWhereColumn('parent_id', 'id');
        });

        $reservas->orderBy('data','desc');

        \UspTheme::activeUrl('/reservas/my');
        return view('reserva.my', [
            'reservas' => $reservas->get(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(GeneralSettings $settings)
    {
        $this->authorize('logado');
        if (Gate::allows('admin')) {
            $categorias = Categoria::with('salas.recursos')->get();
        } else {
            $categoria_id = auth()->user()->categorias->map(function ($item, $key) {
                return $item->id;
            });
            $categorias_list = Categoria::with('salas.recursos')->find($categoria_id);

            $categorias_eca = $categorias_usp = new Collection();

            if (Gate::allows('pessoa.unidade'))
                $categorias_eca = Categoria::where('vinculos', 1)->orWhere('vinculos', 2)->get();

            elseif (Gate::allows('pessoa.usp'))
                $categorias_usp = Categoria::where('vinculos', 2)->get();

            $salas_responsavel_categoria = auth()->user()->salasResponsavel->map(function ($item, $key) {
                return $item->categoria;
            });

            $categorias = new Collection();
            $categorias = $categorias->merge($categorias_list);
            $categorias = $categorias->merge($categorias_eca);
            $categorias = $categorias->merge($categorias_usp);
            $categorias = $categorias->merge($salas_responsavel_categoria);

            $categorias_setores = Categoria::whereHas('setores')->get();

            foreach($categorias_setores as $categoria)
                if(Gate::allows('pessoa.setor', $categoria))
                    $categorias = $categorias->merge([$categoria]);

        }

        $finalidades = Finalidade::all();

        $reserva = new Reserva();
        $reserva->tipo_responsaveis = 'eu';

        foreach($categorias as $categoria)
            foreach($categoria->salas as $salaKey => $sala)
                if(!is_null($sala->restricao) && $sala->restricao->bloqueada) $categoria->salas->forget($salaKey);

        \UspTheme::activeUrl('/reservas/create');
        return view('reserva.create', [
            'irmaos' => false,
            'reserva' => $reserva,
            'settings' => $settings,
            'categorias' => $categorias,
            'finalidades' => $finalidades,
            'salas' => Sala::all(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function store(ReservaRequest $request)
    {
        $validated = $request->validated() + ['user_id' => auth()->user()->id];

        if (isset($validated['extras']))
            $validated['extras'] = json_encode($validated['extras']);

        $this->authorize('members', $validated['sala_id']);

        $sala = Sala::find($validated['sala_id']);

        if($sala->restricao != null && $sala->restricao->aprovacao)
        {
            $validated['status'] = 'pendente';
            $mensagem = "Reserva(s) enviada(s) para análise com sucesso.";
        }
        else
            $mensagem = "Reserva(s) realizada(s) com sucesso.";

        $periodo = GetPeriodoAction::handle($validated);

        if ( count($periodo) > 0 ) {
            $result = CreateReservaAction::handle($validated, $periodo);
            $reserva = $result['reserva'];
            $created = $result['created'];
        }
        else {
            return redirect()->back()->with('alert-danger', 'Operação não completada, não há data(s) para reserva!')->withInput();
        }

        $reserva->reagendarTarefa_AprovacaoAutomatica();

        if(config('salas.emailConfigurado')){
            //enviar e-mail
            if($reserva->status == 'pendente'){
                foreach($reserva->sala->responsaveis as $responsavel)
                {
                    $signedUrls['aprovar'] = URL::signedRoute('reservas.aprovar', ['reserva' => $reserva->id, 'user_id' => $responsavel->id]);
                    $signedUrls['recusar'] = URL::signedRoute('reservas.show', ['reserva' => $reserva->id, 'user_id' => $responsavel->id]);
                    Mail::to($responsavel->email)->queue(new SolicitarReservaMail($reserva, $signedUrls));
                }

                Mail::to($reserva->user->email)->queue(new SolicitarReservaMail($reserva));
            }else{
                Mail::queue(new CreateReservaMail($reserva));
            }
        }

        \UspTheme::activeUrl('/reservas/my');
        session()->put('alert-success', $mensagem . " <ul>$created</ul>");
        return redirect("/reservas/{$reserva->id}");
    }

    /**
     * Display the specified resource.
     *
     * @param \App\Reserva $reserva
     *
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, Reserva $reserva)
    {
        //$this->authorize('members',$reserva->sala_id);
        if($request->hasValidSignature())
        {
            Auth::login(User::find($request->user_id));
        }

        // \UspTheme::activeUrl(($reserva->user_id == auth()->user()->id) ? '/reservas/my' : '');
        return view('reserva.show', [
            'reserva' => $reserva,
            'salas' => Sala::all(),
            ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\Reserva $reserva
     *
     * @return \Illuminate\Http\Response
     */
    public function edit(Reserva $reserva, GeneralSettings $settings)
    {
        $this->authorize('owner', $reserva);
        $this->authorize('reserva.editar', $reserva);

        if (Gate::allows('admin')) {
            $categorias = Categoria::with('salas.recursos')->get();
        } else {
            $categoria_id = auth()->user()->categorias->map(function ($item, $key) {
                return $item->id;
            });
            $categorias_list = Categoria::with('salas.recursos')->find($categoria_id);

            $categorias_eca = $categorias_usp = new Collection();

            if (Gate::allows('pessoa.unidade'))
                $categorias_eca = Categoria::where('vinculos', 1)->orWhere('vinculos', 2)->get();

            elseif (Gate::allows('pessoa.usp'))
                $categorias_usp = Categoria::where('vinculos', 2)->get();

            $categorias = new Collection();
            $categorias = $categorias->merge($categorias_list);
            $categorias = $categorias->merge($categorias_eca);
            $categorias = $categorias->merge($categorias_usp);

        }

        $finalidades = Finalidade::all();

        \UspTheme::activeUrl(($reserva->user_id == auth()->user()->id) ? '/reservas/my' : '');
        return view('reserva.edit', [
            'reserva' => $reserva,
            'settings' => $settings,
            'categorias' => $categorias,
            'finalidades' => $finalidades,
            'salas' => Sala::all(),
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Reserva             $reserva
     *
     * @return \Illuminate\Http\Response
     */
    public function update(ReservaRequest $request, Reserva $reserva)
    {
        $this->authorize('owner', $reserva);

        $validated = $request->validated();
        if (isset($validated['extras']))
            $validated['extras'] = json_encode($validated['extras']);

        $responsaveis = collect();

        switch ($request->tipo_responsaveis) {
            case 'eu':
                $responsavel = ResponsavelReserva::firstOrCreate([
                    'nome' => auth()->user()->name,
                    'codpes' => auth()->user()->codpes
                ]);
                $responsaveis->push($responsavel);

                break;

            case 'unidade':
                foreach ($request->responsaveis_unidade as $responsavel_codpes) {
                    $responsavel = ResponsavelReserva::firstOrCreate([
                        'nome' => Pessoa::obterNome($responsavel_codpes),
                        'codpes' => $responsavel_codpes
                    ]);
                    $responsaveis->push($responsavel);
                }

                break;

            case 'externo':
                foreach($request->responsaveis_externo as $responsavel_nome){
                    $responsavel = ResponsavelReserva::firstOrCreate([
                        'nome' => $responsavel_nome
                    ]);
                    $responsaveis->push($responsavel);
                }

                break;
        }

        $reserva->responsaveis()->sync($responsaveis->pluck('id'));

        // 1 - caso de edição de reserva sem repetições
        if(!isset($request->rep_bool) or empty($request->rep_bool)) {
            $reserva->update($validated);
        }

        // 2 - edição da reserva pai, caso com repeticões, entretanto, foi marcado Não, então as filhos serão excluídos
        if(isset($request->rep_bool) and $request->rep_bool=='Não') {
            $reserva->update($validated);

            foreach($reserva->children()->get() as $child) {
                // não podemos apagar a reserva principal
                if($child->parent_id != $child->id) $child->delete();
                else {
                    $child->parent_id = 0;
                    $child->save();
                }
            }

            \UspTheme::activeUrl(($reserva->user_id == auth()->user()->id) ? '/reservas/my' : '');
            session()->put('alert-danger', 'Reserva atualizada com sucesso. <br>
                                            Entretanto, foi selecionada a opção "Não" para repetição, e portanto
                                            todas reservas filhas foram excluídas!');
            return redirect("/reservas/{$reserva->id}");
        }

        // 3 - edição da reserva pai, caso com repeticões, mas o padrão de repetição foi alterado
        if(isset($request->rep_bool) and $request->rep_bool=='Sim') {
            // deletar as filhas antigas
            foreach($reserva->children()->get() as $child) {
                // não podemos apagar a reserva principal
                if($child->parent_id != $child->id) $child->delete();
            }
            // criar novas reservas
            $validated['id'] = $reserva->id;

            $period = GetPeriodoAction::handle($validated);
            
            $validated['data'] = reset($period)->format('d/m/Y');
            $reserva->update($validated);
            array_shift($period);
            if( count ($period) > 0 ){
                foreach ($period as $date) {
                    $new = $reserva->replicate();
                    $new->parent_id = $reserva->id;
                    $new->data = $date->format('d/m/Y');
                    $new->repeat_until = $validated['repeat_until'];
                    $new->save();
                }
            }else{
                return redirect()->back()->with('alert-danger', 'Operação não completada, não há data(s) para reserva!')->withInput();
            }
        }

        $reserva->reagendarTarefa_AprovacaoAutomatica();

        if(config('salas.emailConfigurado')) Mail::queue(new UpdateReservaMail($reserva));

        \UspTheme::activeUrl(($reserva->user_id == auth()->user()->id) ? '/reservas/my' : '');
        session()->put('alert-success', 'Reserva atualizada com sucesso');
        return redirect("/reservas/{$reserva->id}");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Reserva $reserva
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, Reserva $reserva)
    {
        $this->authorize('owner', $reserva);

        $sala = $reserva->sala;
        $parent_id = $reserva->parent_id;
        $purge = !is_null($request->input('purge'));

        $justificativa = $request->input('justificativa_recusa');

        $reserva->removerTarefa_AprovacaoAutomatica();

        if(config('salas.emailConfigurado'))
            Mail::queue(new DeleteReservaMail($reserva, $purge, $justificativa));

        if($purge){
            Reserva::where('parent_id', $reserva->parent_id)->delete();
            session()->put('alert-success', 'Todas instâncias da reserva foram excluídas com sucesso.');
        }
        else{
            if($reserva->is_parent){
                $reserva->delete();
                $new_parent_id = Reserva::firstWhere('parent_id', $parent_id)->id;
                Reserva::where('parent_id', $parent_id)->update(['parent_id' => $new_parent_id]);
                session()->put('alert-success', 'Reserva excluída com sucesso.');
            }else{
                $reserva->delete();
                session()->put('alert-success', 'Reserva excluída com sucesso.');
            }
        }

        \UspTheme::activeUrl('/salas/listar');
        return redirect()->route('salas.show', [$sala]);
    }

    /**
     * Muda o status da reserva para 'aprovada'.
     *
     * @param \App\Reserva $reserva
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function aprovar(Request $request, Reserva $reserva) {
        if($request->hasValidSignature())
        {
            Auth::login(User::find($request->user_id));
        }

       $this->authorize('responsavel', $reserva->sala);

       if($reserva->parent_id != null){
            // Aprova todas as recorrências da reserva.
            Reserva::where('parent_id', $reserva->parent_id)->get()->map(function($res){
                $res->status = 'aprovada';
                $res->save();
            });
       }else{
            $reserva->status = 'aprovada';
            $reserva->save();
       }

       $reserva->removerTarefa_AprovacaoAutomatica();

       // Enviando e-mail ao ser aprovada.
       if(config('salas.emailConfigurado')) Mail::queue(new CreateReservaMail($reserva));

       \UspTheme::activeUrl(($reserva->user_id == auth()->user()->id) ? '/reservas/my' : '');
       session()->put('alert-success', 'Reserva aprovada com sucesso.');
       return redirect()->route('reservas.show', $reserva->id);
    }
}
