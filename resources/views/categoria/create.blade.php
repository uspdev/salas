@extends('main')
@section('content')
    <form method="POST" action="/categorias">
        @csrf
        @include('categoria.partials.form')
    </form>
@endsection