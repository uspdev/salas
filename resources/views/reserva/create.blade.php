@extends('main')

@section('content')
    <form id="form-reserva" method="POST" action="/reservas" data-ajax="{{ route('SenhaunicaFindUsers') }}">
        @csrf
        @include('reserva.partials.form', ['title' => "Nova reserva"])
    </form>
@endsection

@section('javascripts_bottom')

    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

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
                $('#skip').prop('checked',false);
                $('#repeat_container').hide();
                $('#repFormControl').val('');
            }   
        );

        window.onload = function() {
            if($('#rep_bool_Sim').is(':checked')){
                $('#repeat_container').show();
            }
        }      

        $(document).ready(function() {
            $('.salas_select').select2();
        });

    </script>

    @include('reserva.partials.select2-responsaveis-js')
@stop
