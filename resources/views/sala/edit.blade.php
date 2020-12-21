@extends('main')
@section('content')
  <form method="POST" action="/salas/{{ $sala->id }}">
    @csrf
    @method('patch')
    @include('sala.partials.form')
  </form>
@endsection