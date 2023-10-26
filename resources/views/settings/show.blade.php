@extends('main')
@section('content')
    <form method="POST" action="/settings">
        @csrf
        @include('settings.partials.form')
    </form>
@endsection

@section('styles')
@parent    
<style>
    .config-item{
        color: inherit;
    }
    .config-item:hover{
        background-color: rgba(207, 229, 255, 0.452);
    }
</style>
@endsection