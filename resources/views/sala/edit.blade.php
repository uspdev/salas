@extends('main')
@section('content')
  <form method="POST" action="/sala/{{ $sala->id }}">
    @csrf
    @method('patch')
    @include('sala.partials.form')
  </form>
@endsection