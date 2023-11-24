@extends('main')

@section('content')
    <form method="POST" action="/periodos_letivos">
        @csrf
        @include('periodos_letivos.partials.form', ['title' => "Novo"])
    </form>
@endsection

