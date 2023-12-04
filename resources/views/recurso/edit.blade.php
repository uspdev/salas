@extends('main')

@section('content')
    <form action="{{route('recursos.update', $recurso->id)}}" method="POST">
        @csrf
        @method('PATCH')
        @include('recurso.partials.form', ['title' => 'Editar']) 
    </form>
@endsection