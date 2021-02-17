@extends('main')
@section('title') Sistema de Reserva de Salas @endsection
@section('content')
<div class="card">
    @include('partials.header',['r' => 'active'])
    <div class="card-body">

        @include('partials.admHeader',['c' => 'reservas'])

        <form method="get" action="/reservas">
            <div class="row">
                <div class=" col-sm input-group">
                    <input type="text" class="form-control" name="search" type="text" placeholder="Busca por reserva..." aria-label="default input example" value="{{ request()->search }}">
                    <span class="input-group-btn">
                        <button type="submit" class="btn btn-success"> Buscar </button>
                    </span>
                </div>
            </div>
        </form><br>
        <table class="table table-borderless">
            <div class="table-responsive">
                <tr>
                    <th>Reserva</th>
                    <th>Data</th>
                    <th>Horário</th>
                    <th>Sala</th>
                </tr>
                @forelse($reservas as $reserva)
                <tr>
                    <td> 
                        @can('owner',$reserva)
                            <a href="/reservas/{{ $reserva->id }}">{{ $reserva->nome }}</a>
                        @else
                            {{ $reserva->nome }}
                        @endcan
                    </td>
                    <td>{{ $reserva->data }}</td>
                    <td>{{ $reserva->horario_inicio }} a {{ $reserva->horario_fim }}</td>
                    <td>{{ $reserva->sala->nome }}</td>
                </tr>
                @empty
                    <p>Não há reservas feitas ainda.</p>
                @endforelse
            </div>
        </table>
        {{ $reservas->appends(request()->query())->links() }}
    </div>
</div>
@endsection