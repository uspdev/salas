@extends('main')
@section('content')

<form method="GET" action="/">

@foreach($categorias as $categoria)
  <div class="form-check form-check-inline">
    <input class="form-check-input" type="checkbox" value="{{ $categoria->id }}" id="inlineCheckbox{{ $categoria->id }}" name="filter[]"/>
    <label class="form-check-label" for="inlineCheckbox{{ $categoria->id }}">{{ $categoria->nome }}</label>
  </div>
@endforeach

<button type="submit" class="btn btn-success">Filtrar</button>

</form>

  <br>
  <br>


  {!! $calendar->calendar() !!}
  {!! $calendar->script() !!}

@endsection  