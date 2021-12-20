<?php

namespace App\Http\Controllers;

use App\Models\Sala;
use App\Models\Reserva;
use Illuminate\Http\Request;
use App\Http\Requests\ReservaRequest;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Support\Facades\Gate;
use App\Service\GeneralSettings;

class ReservaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function search(Request $request) 
    {
        if(isset(request()->busca_nome)) {
            $reservas = Reserva::where('nome', 'LIKE',"%{$request->busca_nome}%")->paginate(5);

        } else if(isset(request()->busca_data)) {
            $data = $request->busca_data;
            $data = Carbon::createFromFormat('d/m/Y', $data)->format('Y-m-d');
            $reservas = Reserva::where('data', 'LIKE',"%{$data}%")->paginate(5);

        } else {
            $reservas = Reserva::orderBy('id', 'desc')->paginate(20);  
        }
        /* dd($reservas); */
        return $reservas;
    } 

    public function index(Request $request)
    {  
        $reservas =  $this->search($request); 
        return view('reserva.index',[
            'reservas' => $reservas
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
            $salas = Sala::all();

        } else {
            $categorias = auth()->user()->categorias;
            $salas = collect();
    
            foreach($categorias as $categoria){
                foreach($categoria->salas as $sala){
                    $salas->push($sala);
                }
            }
        }

        return view('reserva.create', [
            'reserva' => new Reserva,
            'salas'   => $salas,
            'settings' => $settings,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ReservaRequest $request)
    {   
        $validated = $request->validated();
        $validated['user_id'] = auth()->user()->id;

        $this->authorize('members',$validated['sala_id']);

        $reserva = Reserva::create($validated);
        $created = '';
        if (array_key_exists("repeat_days", $validated) && array_key_exists("repeat_until", $validated)) {
            $reserva->parent_id = $reserva->id;
            $reserva->save();

            $inicio = Carbon::createFromFormat('d/m/Y', $validated['data']);
            $fim = Carbon::createFromFormat('d/m/Y', $validated['repeat_until']);

            $period = CarbonPeriod::between($inicio, $fim);

            foreach ($period as $date) {
                if(in_array($date->dayOfWeek, $validated['repeat_days'])) {
                    $new = $reserva->replicate();
                    $new->parent_id = $reserva->id;
                    $new->data = $date->format('d/m/Y');
                    $new->save();
                    $created .= "<li><a href='/reservas/{$new->id}'> {$date->format('d/m/Y')}- {$new->nome}</a></li>";
                }
            }
        }

        request()->session()->flash('alert-info', "Reserva(s) realizada(s) com sucesso. <ul>{$created}</ul>");
        return redirect("/salas/{$reserva->sala->id}");
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Reserva  $reserva
     * @return \Illuminate\Http\Response
     */
    public function show(Reserva $reserva)
    {
        #$this->authorize('members',$reserva->sala_id);

        return view('reserva.show',[
            'reserva' => $reserva
            ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Reserva  $reserva
     * @return \Illuminate\Http\Response
     */
    public function edit(Reserva $reserva, GeneralSettings $settings)
    {
        $this->authorize('owner',$reserva);

        if (Gate::allows('admin')) {
            $salas = Sala::all();

        } else {
            $categorias = auth()->user()->categorias;
            $salas = collect();
    
            foreach($categorias as $categoria){
                foreach($categoria->salas as $sala){
                    $salas->push($sala);
                }
            }
        }

        if($reserva->parent_id !=null) {
            request()->session()->flash('alert-danger', "
            Atenção: Essa reserva faz parte de grupo e você está editando somente essa instância");
        }
        return view('reserva.edit', [
            'reserva'  => $reserva,
            'salas'    => $salas,
            'settings' => $settings,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Reserva  $reserva
     * @return \Illuminate\Http\Response
     */
    public function update(ReservaRequest $request, Reserva $reserva)
    {
        $this->authorize('owner',$reserva);

        $validated = $request->validated();
        $reserva->update($validated);
        request()->session()->flash('alert-info', "Reserva atualizada com sucesso");
        return redirect("/reservas/{$reserva->id}");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Reserva  $reserva
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, Reserva $reserva)
    {
        $this->authorize('owner',$reserva);

        if($request->tipo == 'one'){
            $reserva->delete();
            request()->session()->flash('alert-info', 'Reserva excluída com sucesso.');
        } else if($request->tipo == 'all'){
            Reserva::where('parent_id',$reserva->parent_id)->delete();
            request()->session()->flash('alert-info', 'Todas Instâncias foram excluídas com sucesso.');
        }

        return redirect('/reservas');
    }
}
