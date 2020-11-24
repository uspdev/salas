@extends('main')
@section('content')
    <form method="POST" action="/reserva">
        @csrf
        @include('reserva.partials.form')
    </form>
@endsection