@extends('main')
@section('title') Sistema de Reserva de Salas @endsection
@section('content')
<form method="get" action="/salas">
<div class="row">
    <div class=" col-sm input-group">
    <input type="text" class="form-control" name="search" value="{{ request()->search }}">

    <span class="input-group-btn">
        <button type="submit" class="btn btn-success"> Buscar </button>
    </span>

    </div>
</div>
</form><br>
@forelse($salas as $sala)
@include('sala.partials.fields')
@empty
    <p>Não há salas cadastradas ainda.</p>
@endforelse
{{ $salas->appends(request()->query())->links() }}
@endsection