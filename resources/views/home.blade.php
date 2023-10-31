@extends('main')

@section('content')
    @include('reserva.index')
@endsection  

@section('javascripts_bottom')
    <script>
        $('#input_busca_data').datepicker();
    </script>
@endsection