@extends('main')
@section('content')
    <form method="POST" action="/recursos">
        @csrf
        @include('recurso.partials.form')
    </form>
@endsection