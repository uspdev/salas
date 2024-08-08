<?php

namespace App\Http\Controllers;

use App\Http\Requests\ReservaRequest;
use App\Models\Reserva;
use App\Models\Sala;
use App\Models\Categoria;
use App\Models\Recurso;
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
use Spatie\Permission\Models\Permission;
use Uspdev\Replicado\Pessoa;

class ReservaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function my(Request $request)
    {
        $this->authorize('logado');
        $reservas = Reserva::where('user_id', auth()->user()->id);

        // Só estamos interessados nas reservas de maior hierarquia
        $reservas->where(function ($query) {
            $query->whereNull('parent_id')->orWhereColumn('parent_id', 'id');
        });

        $reservas->orderBy('data','desc');

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

            $categorias = new Collection();
            $categorias = $categorias->merge($categorias_list);
            $categorias = $categorias->merge($categorias_eca);
            $categorias = $categorias->merge($categorias_usp);

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

        return view('reserva.create', [
            'irmaos' => false,
            'reserva' => $reserva,
            'settings' => $settings,
            'categorias' => $categorias,
            'finalidades' => $finalidades,
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
        $validated = $request->validated();
        $validated['user_id'] = auth()->user()->id;

        $this->authorize('members', $validated['sala_id']);

        $sala = Sala::find($validated['sala_id']);

        if($sala->restricao != null && $sala->restricao->aprovacao)
        {
            $validated['status'] = 'pendente';
            $mensagem = "Reserva(s) enviada(s) para análise com sucesso.";
        }
        else 
            $mensagem = "Reserva(s) realizada(s) com sucesso.";

        $reserva = Reserva::create($validated);

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

        $created = '';
        if (array_key_exists('repeat_days', $validated) && array_key_exists('repeat_until', $validated)) {
            $reserva->parent_id = $reserva->id;
            $reserva->save();

            $inicio = Carbon::createFromFormat('d/m/Y', $validated['data'])->addDays(1);
            $fim = Carbon::createFromFormat('d/m/Y', $validated['repeat_until']);

            $period = CarbonPeriod::between($inicio, $fim);

            foreach ($period as $date) {
                if (in_array($date->dayOfWeek, $validated['repeat_days'])) {
                    $new = $reserva->replicate();
                    $new->parent_id = $reserva->id;
                    $new->data = $date->format('d/m/Y');
                    $new->save();
                    $new->responsaveis()->sync($responsaveis->pluck('id'));
                    $created .= "<li><a href='/reservas/{$new->id}'> {$date->format('d/m/Y')}- {$new->nome}</a></li>";
                }
            }
        }

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

        return redirect("/reservas/{$reserva->id}")
            ->with('alert-success', $mensagem . " <ul>{$created}</ul>");
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

        return view('reserva.show', [
            'reserva' => $reserva,
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

        return view('reserva.edit', [
            'reserva' => $reserva,
            'settings' => $settings,
            'categorias' => $categorias,
            'finalidades' => $finalidades
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

            return redirect("/reservas/{$reserva->id}")
                     ->with('alert-danger', 'Reserva atualizada com sucesso. <br> 
                             Entretanto, foi selecionada a opção "Não" para repetição, e portanto 
                             todas reservas filhas foram excluídas!');
        }

        // 3 - edição da reserva pai, caso com repeticões, mas o padrão de repetição foi alterado
        if(isset($request->rep_bool) and $request->rep_bool=='Sim') {
            $reserva->update($validated);
            // deletar as filhas antigas
            foreach($reserva->children()->get() as $child) {
                // não podemos apagar a reserva principal
                if($child->parent_id != $child->id) $child->delete();
            }

            // criar novas revervas
            $inicio = Carbon::createFromFormat('d/m/Y', $validated['data'])->addDays(1);
            $fim = Carbon::createFromFormat('d/m/Y', $validated['repeat_until']);

            $period = CarbonPeriod::between($inicio, $fim);
            foreach ($period as $date) {
                if (in_array($date->dayOfWeek, $validated['repeat_days'])) {
                    $new = $reserva->replicate();
                    $new->parent_id = $reserva->id;
                    $new->data = $date->format('d/m/Y');
                    $new->save();
                }
            }

        }

        if(config('salas.emailConfigurado')) Mail::queue(new UpdateReservaMail($reserva));

        return redirect("/reservas/{$reserva->id}")
            ->with('alert-success', 'Reserva atualizada com sucesso');
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

        if(config('salas.emailConfigurado')) Mail::queue(new DeleteReservaMail($reserva, $purge));

        if($purge){
            Reserva::where('parent_id', $reserva->parent_id)->delete();
            request()->session()->flash('alert-success', 'Todas instâncias da reserva foram excluídas com sucesso.');
        }
        else{
            if($reserva->is_parent){
                $reserva->delete();
                $new_parent_id = Reserva::firstWhere('parent_id', $parent_id)->id;
                Reserva::where('parent_id', $parent_id)->update(['parent_id' => $new_parent_id]);
                request()->session()->flash('alert-success', 'Reserva excluída com sucesso.');
            }else{
                $reserva->delete();
                request()->session()->flash('alert-success', 'Reserva excluída com sucesso.');
            }
        }

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

       // Enviando e-mail ao ser aprovada.
       if(config('salas.emailConfigurado')) Mail::queue(new CreateReservaMail($reserva));

       return redirect()->route('reservas.show', $reserva->id)->with('alert-success', 'Reserva aprovada com sucesso.');
    }
}
