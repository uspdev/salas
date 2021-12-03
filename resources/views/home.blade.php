@extends('main')
@section('content')

    <div class="card">
        <div class="card-header">
            <h5 class="card-title">Filtrar reservas por categoria</h5>
        </div>
        <div class="card-body">

            <form method="GET" action="/">

            @foreach($categorias as $categoria)
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="checkbox" value="{{  old('filter.0') }}" id="inlineCheckbox{{ $categoria->id }}" name="filter[]"/>
                    <label class="form-check-label" for="inlineCheckbox{{ $categoria->id }}">{{ $categoria->nome }}</label>
                </div>
            @endforeach
            <br>
            <button style="margin-top: 1%;" type="submit" class="btn btn-success">Filtrar</button>

            </form>

        </div>
    </div>
    <br>

    {!! $calendar->calendar() !!}
    {!! $calendar->script() !!}

@endsection  
