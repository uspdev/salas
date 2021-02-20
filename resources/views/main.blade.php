@extends('laravel-usp-theme::master')

@section('styles')
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>
    <script src='https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.10.2/fullcalendar.min.js'></script>
    <script src='https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.10.2/locale/pt-br.min.js'></script>
    <link rel='stylesheet' href="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.1.0/fullcalendar.min.css" />
    <link rel='stylesheet' href="{{ asset('assets/css/calendar.css') }}" />
    <link rel='stylesheet' href="{{ asset('assets/css/app.css') }}">
@endsection

<!-- datepicker é carregado por padrão em public/assets/js/datepicker.js -->

@section('javascripts_bottom')
    @parent
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/locales/bootstrap-datepicker.pt-BR.min.js"></script> 
    <script src="{{ asset('assets/js/app.js') }}" type="text/javascript"></script>
    <script src="{{ asset('assets/js/datepicker.js') }}" type="text/javascript"></script>
@endsection

@section('flash')
    @if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
            <li>{!! $error !!}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <div class="flash-message">
    @foreach (['danger', 'warning', 'success', 'info'] as $msg)
        @if(Session::has('alert-' . $msg))
        <p class="alert alert-{{ $msg }}">{!! Session::get('alert-' . $msg) !!}
            <a href="#" class="close" data-dismiss="alert" aria-label="fechar">&times;</a>
        </p>
        @endif
    @endforeach
    </div>
@endsection

