@extends('main')
@section('content')
    <form method="POST" action="/sala">
        @csrf
        @include('sala.partials.form')
    </form>
@endsection