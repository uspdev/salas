@extends('main')

@section('styles')
  @parent
  <style>
    .dot {
    height: 12px;
    width: 12px;
    border-radius: 50%;
    display: inline-block;
    }
  </style>
@endsection

@section('content')  

    <div class="card">
        <div class="card-header">
            <div class="card-title"><b>Reservas de hoje: {{\Carbon\Carbon::now()->format('d/m/Y')}}</b></div>
        </div>
        <div class="card-body">
            <form method="GET" action="/">
                <div class="form-row">
                    @foreach($categorias as $categoria)
                            <div class="form-group col-md-3">
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="checkbox" value="{{ $categoria->id }}" id="inlineCheckbox{{ $categoria->id }}" name="filter[]" @if(in_array($categoria->id, $filter))  checked  @endif/>
                                    <label class="form-check-label" for="inlineCheckbox{{ $categoria->id }}">{{ $categoria->nome }}</label>
                                </div>
                            </div>
                    @endforeach
                </div>
                <button type="submit" class="btn btn-success">Filtrar</button>

            </form>

            <br>
            <table class="table">
            <tbody>
                @forelse($reservas as $reserva)
                <tr>
                    <td>{{ $reserva->horario_inicio }} - {{$reserva->horario_fim}}</td>
                    <td><span class="dot" style="background-color:{{$reserva->cor}};"></span></td>
                    <td><a href="/reservas/{{ $reserva->id }}">{{ $reserva->nome }}</a></td>
                    <td>{{ $reserva->sala->categoria->nome }} </td>
                    <td>{{ $reserva->sala->nome  }} </td>
                </tr>
                @empty 
                <tr><td>Não há reservas registradas para hoje </td></tr>
                @endforelse
            </tbody>
            </table>
        </div>
    </div>
@endsection  
