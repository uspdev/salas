@extends('main')
@section('content')
    <form method="POST" action="/categorias/{{ $categoria->id }}">
        @csrf
        @method('patch')
        @include('categoria.partials.form', ['title' => "Editar"])
    </form>
@endsection