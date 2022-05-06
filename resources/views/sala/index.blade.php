@extends('main')
@section('title') 
    Sistema de Reserva de Salas 
@endsection
@section('content')
<div class="card">
    <div class="card-header">   
        <h5 class="card-title">Calendário por Sala</h5>
    </div>
    <div class="card-body">
        <ul class="list-group list-group-flush">
            @foreach($categorias as $categoria)
            <li class="list-group-item">
                <div class="card">
                    <div class="card-header" type="button" data-toggle="collapse" data-target="#collapse{{ $categoria->id }}" aria-expanded="false" aria-controls="collapse{{ $categoria->id }}">
                        @can('admin')
                            <a href="/categorias/{{ $categoria->id }}">{{ $categoria->nome }}</a>
                        @else
                            {{ $categoria->nome }}
                        @endcan
                        <i class="far fa-plus-square"></i>
                    </div>
                    <ul class="list-group list-group-flush">
                        <div class="collapse" id="collapse{{ $categoria->id }}">
                            <div class="card-body">
                                @include('sala.partials.table', ['salas' => $categoria->salas])
                            </div>
                        </div>
                    </ul>
                </div>
            </li>
            @endforeach
        </ul>
    </div>  
</div>
@endsection
