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
  </div><br>
    <a class="btn btn-outline-dark" href="/salas" role="button">
      <i class="fas fa-arrow-left"></i> Voltar
    </a><br>
  <br>
  {!! $calendar->calendar() !!}
  {!! $calendar->script() !!}

@endsection  