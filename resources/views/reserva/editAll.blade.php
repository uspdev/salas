@extends('main')

@section('content')
    <form method="POST" action="/reservas/updateAll/{{ $reserva->id }}">
        @csrf
        @include('reserva.partials.form', ['title' => "Editar reservas", 'editOne' => false])
    </form>
@endsection