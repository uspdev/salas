@extends('main')
@section('content')
    <form method="POST" action="/settings">
        @csrf
        @include('settings.partials.form')
    </form>
    <div class="card mt-3">
        <div class="card-header">Finalidades</div>
        <div class="card-body">
            <a class="btn btn-primary" href="{{route('finalidades.index')}}">Finalidades</a>
        </div>
    </div>
@endsection