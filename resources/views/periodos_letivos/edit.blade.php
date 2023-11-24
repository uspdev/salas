@extends('main')
@section('content')
    <form action="{{route('periodos_letivos.store')}}" method="POST" id='form-add-periodo-letivo'>@csrf</form>
    <form method="POST" id="form-delete-periodo-letivo">@csrf @method('DELETE')</form>
    <form method="POST" action="/periodos_letivos/{{ $periodo->id }}" id="form-update-periodo-letivo">
        @csrf
        @method('patch')
        @include('periodos_letivos.partials.form', ['title' => "Editar"])
    </form>
@endsection
