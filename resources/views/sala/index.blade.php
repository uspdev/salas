@extends('main')
@section('title') 
    Sistema de Reserva de Salas 
@endsection
@section('styles')
    <style>
        #categoria-header {
            display: flex;
            justify-content: space-between;
        }
    </style>
@endsection

@section('content')
    <div class="card">
        <div class="card-header">   
            <h5 class="card-title">Calendário por Sala</h5>
        </div>
        <div class="card-body">
            <div class="input-group">
                <select id="select-salas" class="form-control" name="sala" onchange="changeUrlFromSalaId()">
                    @foreach($salas as $sala)
                        <option value="{{ $sala->id }}">{{ $sala->nome }} - {{ $sala->categoria->nome }}</option>
                    @endforeach
                </select>
                <a id="a-salas" class="btn btn-outline-success"><i class="fas fa-arrow-circle-right"></i></a>
            </div>
            <ul class="list-group list-group-flush">
                @foreach($categorias as $categoria)
                <li class="list-group-item">
                    <div class="card">
                        <div class="card-header" id="categoria-header" type="button" data-toggle="collapse" data-target="#collapse{{ $categoria->id }}" aria-expanded="false" aria-controls="collapse{{ $categoria->id }}">
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

@section('javascripts_bottom')
    <script>
        $(document).ready(function() {
            $('#select-salas').select2();
        });

        function changeUrlFromSalaId() {
            var sel = document.getElementById('select-salas');
            var a = document.getElementById('a-salas'); 
            var value = sel.value
            a.href = "/salas/" + value;
            console.log(value);
        }

        window.onload = changeUrlFromSalaId;
    </script>
@endsection