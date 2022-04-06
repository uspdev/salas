@extends('main')
@section('title') 
    Sistema de Reserva de Salas 
@endsection
@section('content')
    <div class="card">
        <div class="card-body">
            <form method="get" action="/salas">
                <div class="row">
                    <div class=" col-sm input-group">
                        <input type="text" class="form-control" name="search" type="text" placeholder="Busca por sala" aria-label="default input example" value="{{ request()->search }}">
                        <span class="input-group-btn">
                            <button type="submit" class="btn btn-success"> Buscar </button>
                        </span>
                    </div>
                </div>
            </form><br>
            {{ $salas->appends(request()->query())->links() }}
            <table class="table table-borderless">
                <div class="table-responsive">
                    <tr>
                        <th>Sala</th>
                        <th>Categoria</th>
                        <th>Capacidade</th>
                    </tr>
                    @forelse($salas as $sala)
                    <tr>
                        <td><a href="/salas/{{ $sala->id }}">{{ $sala->nome }}</a></td>
                        <td>{{ $sala->categoria->nome }}</td>
                        <td>{{ $sala->capacidade }}</td>
                    </tr>
                    @empty
                        <p>Não há salas cadastradas ainda.</p>
                    @endforelse
                </div>
            </table>
        </div>
    </div>
@endsection
