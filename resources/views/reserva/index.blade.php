@extends('main')
@section('title') Sistema de Reserva de Salas @endsection
@section('content')
<form method="get" action="/reservas">
<div class="row">
    <div class=" col-sm input-group">
    <input type="text" class="form-control" name="search" value="{{ request()->search }}">

    <span class="input-group-btn">
        <button type="submit" class="btn btn-success"> Buscar </button>
    </span>
    </div>
</div>
</form><br>
<a class="btn btn-primary" href="/salas" role="button">Salas</a><br>
@forelse($reservas as $reserva)
@include('reserva.partials.fields')
@empty
    <p>Não há reservas feitas.</p>
@endforelse
{{ $reservas->appends(request()->query())->links() }}
@endsection