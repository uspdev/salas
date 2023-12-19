@extends('main')

@section('content')
    @include('reserva.index')
@endsection  

@section('javascripts_bottom')
    <script>
        $('#input_busca_data').datepicker({
            dateFormat: 'dd/mm/yy',
            closeText:"Fechar",
            prevText:"Anterior",
            nextText:"Próximo",
            currentText:"Hoje",
            monthNames: ["Janeiro","Fevereiro","Março","Abril","Maio","Junho","Julho","Agosto","Setembro","Outubro","Novembro","Dezembro"],
            monthNamesShort:["Jan","Fev","Mar","Abr","Mai","Jun","Jul","Ago","Set","Out","Nov","Dez"],
			dayNames:["Domingo","Segunda-feira","Terça-feira","Quarta-feira","Quinta-feira","Sexta-feira","Sábado"],
			dayNamesShort:["Dom","Seg","Ter","Qua","Qui","Sex","Sáb"],
            dayNamesMin:["Dom","Seg","Ter","Qua","Qui","Sex","Sáb"],
        });

        $(document).ready(function() {
            $("#btn-limpar-filtros").on('click', function(){
                $('#form-filtros').find(':input').val('');
                $('.select2').val('val').trigger('change');
            });
        });
    </script>
@endsection