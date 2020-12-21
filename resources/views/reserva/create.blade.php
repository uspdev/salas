@extends('main')
@section('content')
    <form method="POST" action="/reservas">
        @csrf
        @include('reserva.partials.form')
    </form>
@endsection