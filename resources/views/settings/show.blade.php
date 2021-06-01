@extends('main')
@section('content')
    <form method="POST" action="/settings">
        @csrf
        @include('settings.partials.form')
    </form>
@endsection