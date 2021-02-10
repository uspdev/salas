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
                <a class="nav-link active" aria-current="true" href="/reservas"><b>Salas</b></a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="/reservas"><b>Reservas</b></a>
            </li>
        </ul>    
    </div>
        <div class="card-body">
            <form method="get" action="/salas">
                <div class="row">
                    <div class=" col-sm input-group">
                        <input type="text" class="form-control" name="search" type="text" placeholder="Busca por sala..." aria-label="default input example" value="{{ request()->search }}">
                        <span class="input-group-btn">
                            <button type="submit" class="btn btn-success"> Buscar </button>
                        </span>
                    </div>
                </div>
            </form><br>
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
                        <td>{{ $sala->categoria_id }}</td>
                        <td>{{ $sala->capacidade }}</td>
                    </tr>
                    @empty
                        <p>Não há salas cadastradas ainda.</p>
                    @endforelse
                </div>
            </table>
            {{ $salas->appends(request()->query())->links() }}
    </div>
</div>
@endsection