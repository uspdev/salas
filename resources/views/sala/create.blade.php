@extends('main')
@section('content')
    <form method="POST" action="/salas">
        @csrf
        @include('sala.partials.form')
    </form>
@endsection