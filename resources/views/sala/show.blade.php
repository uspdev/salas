@extends('main')
@section('content')
    <div class="card">
        <div class="card-header" type="button" data-toggle="collapse" data-target="#collapse{{ $sala->id }}" aria-expanded="false" aria-controls="collapse{{ $sala->id }}">
            <span><b>{{ $sala->nome }}</b></span>
            <i class="far fa-plus-square"></i>
        </div>
    </div>
    <div class="collapse" id="collapse{{ $sala->id }}">
        <div class="card card-body">
            @include('sala.partials.fields')
        </div>
    </div>
    <div class="d-flex flex-wrap w-100 justify-content-center mt-3">
        @foreach ($finalidades as $finalidade)
            <div class="flex-item rounded" style="background-color: {{$finalidade->cor}}">{{$finalidade->legenda}}</div>
        @endforeach
            <div class="flex-item rounded" style="background-color: {{config('salas.cores.pendente')}}">Pendente</div>
            <div class="flex-item rounded" style="background-color: {{config('salas.cores.semFinalidade')}}">Sem Finalidade</div>
    </div>

    <br><br>
    {!! $calendar->calendar() !!}
    {!! $calendar->script() !!}
@endsection  

@section('styles')
@parent
<style>
    .flex-item{
        text-align: center;
        padding: 5px 20px;
        margin: 5px;
        border: 1px solid black;
    }
</style>
@endsection