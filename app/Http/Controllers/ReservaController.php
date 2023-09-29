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
use App\Mail\UpdateReservaMail;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Mail;

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

        } 

        return view('reserva.create', [
            'irmaos' => false,
            'reserva' => new Reserva(),
            'settings' => $settings,
            'categorias' => $categorias,
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

        if(Sala::find($validated['sala_id'])->aprovacao)
        {
            $validated['status'] = 'pendente';
            $mensagem = "Reserva(s) enviada(s) para análise com sucesso.";
        }
        else 
            $mensagem = "Reserva(s) realizada(s) com sucesso.";

        $reserva = Reserva::create($validated);
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
                    $created .= "<li><a href='/reservas/{$new->id}'> {$date->format('d/m/Y')}- {$new->nome}</a></li>";
                }
            }
        }
        //enviar e-mail
        Mail::queue(new CreateReservaMail($reserva));

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
    public function show(Reserva $reserva)
    {
        //$this->authorize('members',$reserva->sala_id);

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

        if (Gate::allows('admin')) {
            $categorias = Categoria::with('salas.recursos')->get();
        } else {
            $categoria_id = auth()->user()->categorias->map(function ($item, $key) {
                return $item->id;
            });
            $categorias = Categoria::with('salas.recursos')->find($categoria_id);
        }

        return view('reserva.edit', [
            'reserva' => $reserva,
            'settings' => $settings,
            'categorias' => $categorias,
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

        // 1 - caso de edição de reserva sem repetições
        if(!isset($request->rep_bool) or empty($request->rep_bool)) {
            $reserva->update($request->validated());
        }
        
        // 2 - edição da reserva pai, caso com repeticões, entretanto, foi marcado Não, então as filhos serão excluídos
        if(isset($request->rep_bool) and $request->rep_bool=='Não') {
            $reserva->update($request->validated());

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
            $validated = $request->validated();
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

        Mail::queue(new UpdateReservaMail($reserva));

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

        Mail::queue(new DeleteReservaMail($reserva));

        $parent_id = null;
        if($reserva->parent_id != null) {
            $parent_id = $reserva->parent_id;
        }

        if($reserva->is_parent){
            Reserva::where('parent_id', $reserva->parent_id)->delete();
            request()->session()->flash('alert-success', 'Todas instâncias da reserva foram excluídas com sucesso.');
            return redirect('/');
        } else {
            $reserva->delete();
            request()->session()->flash('alert-success', 'Reserva excluída com sucesso.');
        }

        if($parent_id == null) {
            return redirect('/');
        } else {
            return redirect('/reservas/' . $parent_id);
        }
        
    }
}
