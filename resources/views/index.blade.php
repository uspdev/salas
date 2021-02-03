@extends('main')

@section('content')

@foreach($categorias as $categoria)

- <b>{{$categoria->nome}}</b> <br>
  @foreach($categoria->salas as $sala)
  -- <a href="/salas/{{ $sala->id }}">{{ $sala->nome }} </a><br>
  @endforeach

@endforeach

@endsection
