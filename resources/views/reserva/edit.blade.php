@extends('main')
@section('content')
  <form method="POST" action="/reservas/{{ $reserva->id }}">
    @csrf
    @method('patch')
    @include('reserva.partials.form')
  </form>
@endsection