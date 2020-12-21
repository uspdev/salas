@extends('main')
@section('title') Sistema de Reserva de Salas @endsection
@section('content')
@forelse($categorias as $categoria)
@include('categoria.partials.fields')
@empty
    <p>Não há categorias cadastradas ainda.</p>
@endforelse
@endsection