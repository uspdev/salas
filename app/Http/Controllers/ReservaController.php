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
use App\Mail\UpdateReservaMail;
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
            $salas = Sala::all();
        } else {
            $salas = auth()->user()->salas;
        }

        return view('reserva.create', [
            'irmaos' => false,
            'reserva' => new Reserva(),
            'salas' => $salas,
            'settings' => $settings,
            'categorias' => Categoria::all(),
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
            ->with('alert-success', "Reserva(s) realizada(s) com sucesso. <ul>{$created}</ul>");
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
            $salas = Sala::all();
        } else {
            $salas = auth()->user()->salas;
        }

        if ($reserva->parent_id != null) {
            request()->session()->flash('alert-warning', 'Atenção: Esta reserva faz parte de um grupo e você está editando somente esta instância');
        }

        return view('reserva.edit', [
            'reserva' => $reserva,
            'salas' => $salas,
            'settings' => $settings,
        ]);
    }

    public function editAll(Reserva $reserva, GeneralSettings $settings)
    {
        $this->authorize('owner', $reserva);

        return view('reserva.editAll', [
            'reserva' => $reserva,
            'settings' => $settings,
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

        $reserva->update($request->validated());

        Mail::queue(new UpdateReservaMail($reserva));

        return redirect("/reservas/{$reserva->id}")
            ->with('alert-success', 'Reserva atualizada com sucesso');
    }

    public function updateAll(Request $request, Reserva $reserva)
    {
        $this->authorize('owner', $reserva);

        $irmaos = $reserva->irmaos(); //delete irmãos antigos
        $request->validate([
                'nome' => 'required',
            ],
            [
                'nome.required' => 'O título não pode ficar em branco.',
            ]);

        $irmaos->each(function ($item) use ($request) {
            $item['nome'] = $request->nome;
            $item['cor'] = $request->cor;
            $item['descricao'] = $request->descricao;
            $item->update();
        });

        Mail::queue(new UpdateReservaMail($reserva));

        return redirect("/reservas/{$reserva->id}")
            ->with('alert-success', 'Reserva atualizadas com sucesso');
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

        if ($request->tipo == 'one') {
            $reserva->delete();
            request()->session()->flash('alert-success', 'Reserva excluída com sucesso.');
        } elseif ($request->tipo == 'all') {
            Reserva::where('parent_id', $reserva->parent_id)->delete();
            request()->session()->flash('alert-success', 'Todas Instâncias foram excluídas com sucesso.');
        }

        return redirect('/');
    }
}
