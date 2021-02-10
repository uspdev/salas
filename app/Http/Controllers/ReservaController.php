<?php

namespace App\Http\Controllers;

use App\Models\Reserva;
use Illuminate\Http\Request;
use App\Http\Requests\ReservaRequest;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
use Carbon\CarbonPeriod;

class ReservaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {   
        if(isset(request()->search)){
            $reservas = Reserva::where('nome', 'LIKE',"%{$request->search}%")->paginate(5);
                                    # ->orwhere('categoria', 'LIKE',"%{$request->search}%")->paginate(5);
        }

        else{
        $reservas = Reserva::paginate(20);
        }

        return view('reserva.index',[
            'reservas' => $reservas
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('reserva.create', [
            'reserva' => new Reserva,
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
        $reserva = Reserva::create($validated);

        $created = '';
        if (array_key_exists("repeat_days", $validated) && array_key_exists("repeat_until", $validated)) {
            $reserva->parent_id = $reserva->id;
            $reserva->save();

            $inicio = Carbon::createFromFormat('d/m/Y', $validated['data']);
            $fim = Carbon::createFromFormat('d/m/Y', $validated['repeat_until']);

            $period = CarbonPeriod::between($inicio, $fim);

            foreach ($period as $date) {
                if(in_array($date->dayOfWeek, $validated['repeat_days'])){
                    $new = $reserva->replicate();
                    $new->parent_id = $reserva->id;
                    $new->data = $date->format('d/m/Y');
                    $new->save();
                    $created .= "<li><a href='/reservas/{$new->id}'> {$date->format('d/m/Y')}- {$new->nome}</a></li>";
                }
            }
        }

        request()->session()->flash('alert-info', "Reserva(s) realizada(s) com sucesso. <ul>{$created}</ul>");
        return redirect("/reservas/{$reserva->id}");
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Reserva  $reserva
     * @return \Illuminate\Http\Response
     */
    public function show(Reserva $reserva)
    {
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
    public function edit(Reserva $reserva)
    {
        if($reserva->parent_id !=null) {
            request()->session()->flash('alert-danger', "
            Atenção: Essa reversa faz parte de grupo e você está editando somente essa instância");
        }
        return view('reserva.edit', [
            'reserva' => $reserva
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
