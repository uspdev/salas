@extends('main')
@section('content')
    <form method="POST" action="/recursos/{{ $recurso->id }}">
        @csrf
        @method('patch')
        @include('recurso.partials.form')
    </form>
@endsection