@extends('main')
@section('content')
  <div class="card">
    <div class="card-header" type="button" data-toggle="collapse" data-target="#collapse{{ $sala->id }}" aria-expanded="false" aria-controls="collapse{{ $sala->id }}">
      <span><b>{{ $sala->nome }}</b></span>
      <i class="fas fa-plus"></i>
    </div>
  </div>
  <div class="collapse" id="collapse{{ $sala->id }}">
    <div class="card card-body">
      @include('sala.partials.fields')
    </div>
  </div><br>
  <div class="d-grid gap-2 d-md-flex justify-content-md-end">
    <a class="btn btn-outline-dark" href="/salas" role="button">
      <i class="fas fa-plus"></i>
    </a>
  </div>
  <br>{!! $calendar->calendar() !!}
  {!! $calendar->script() !!}

@endsection  