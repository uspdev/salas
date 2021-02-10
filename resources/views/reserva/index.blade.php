@extends('main')
@section('title') Sistema de Reserva de Salas @endsection
@section('content')
    <div class="card">
        <div class="card-header">
            <ul class="nav nav-tabs card-header-tabs">
                <li class="nav-item">
                    <a class="nav-link" href="/"><b>Categorias</b></a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="/salas"><b>Salas</b></a>
                </li>
                <li class="nav-item">
                    <a class="nav-link active" aria-current="true" href="/reservas"><b>Reservas</b></a>
                </li>
            </ul>        
        </div>
            <div class="card-body">
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
                            <td><a href="/reservas/{{ $reserva->id }}">{{ $reserva->nome }}</a></td>
                            <td>{{ $reserva->data }}</td>
                            <td>{{ $reserva->horario_inicio }} a {{ $reserva->horario_fim }}</td>
                            <td>{{ $reserva->sala_id }}</td>
                        </tr>
                        @empty
                            <p>Não há reservas feitas ainda.</p>
                        @endforelse
                    </div>
                </table>
                {{ $reservas->appends(request()->query())->links() }}
            </div>
        </div>
    </div>
@endsection