@extends('main')
@section('content')
    <form method="POST" action="/reservas">
        @csrf
        @include('reserva.partials.form')
    </form>
@endsection
@section('javascripts_bottom')
    <script type="text/javascript">

        function collapse() {
            document.getElementById("repeat_container").style.display = "flex";
          }

        function hide() {
            document.getElementById("repeat_container").style.display = "none";
        }
        
        $('#rep_bool_Sim').click( function() { 
                $('#repeat_container').show();
            }   
        );

        $('#rep_bool_Nao').click( function() { 
                $('#repeat_container').hide();
            }   
        );

        window.onload = function() {
            if($('#rep_bool_Sim').is(':checked')){
                $('#repeat_container').show();
            }
        };      
    </script>
@stop
