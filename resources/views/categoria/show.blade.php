@extends('main')
@section('content')

  @include('categoria.partials.fields') 

  Pessoa nesta categoria:
  <br>
  @foreach($categoria->users as $user)
    {{ $user->codpes }} - {{ $user->name }} <br>
  @endforeach

  <br>
  <form method="POST" action="/categorias/adduser/{{ $categoria->id }}">
    @csrf
    Adicionar pessoa na Categoria, Número USP: <input name="codpes">
    <button type="submit">Enviar</button>
  </form>
  
@endsection  