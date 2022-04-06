@extends('main')
@section('title') 
    Sistema de Reserva de Salas 
@endsection
@section('content')
    @forelse($recursos as $recurso)
        @include('recurso.partials.fields')
    @empty
        <p>Não há recursos cadastrados ainda.</p>
    @endforelse
@endsection