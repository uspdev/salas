@extends('main')
@section('title') Sistema de Reserva de Salas @endsection
@section('content')
@forelse($reservas as $reserva)
@include('reserva.partials.fields')
@empty
    <p>Não há reservas feitas.</p>
@endforelse
@endsection