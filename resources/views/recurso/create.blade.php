@extends('main')

@section('content')
    <form action="{{route('recursos.store')}}" method="POST">
        @csrf
        @include('recurso.partials.form', ['title' => 'Novo']) 
    </form>
@endsection