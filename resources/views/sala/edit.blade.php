@extends('main')
@section('content')
    <form action="{{route('responsaveis.store')}}" method="POST" id='form-add-responsavel'>@csrf</form>
    <form method="POST" id="form-delete-responsavel">@csrf @method('DELETE')</form>
    <form method="POST" action="/salas/{{ $sala->id }}" id="form-update-sala">
        @csrf
        @method('patch')
        @include('sala.partials.form', ['title' => "Editar"])
    </form>
@endsection

@section('javascripts_bottom')
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.5/dist/jquery.validate.min.js"></script>

    <script type="text/javascript">
        $(document).ready(function() {
            $('.categorias_select').select2();
        });

       $('.radio-aprovacao').on('click', function(e){
            if(parseInt($(this).val()))
                $('#responsavel-box').css('display', 'block');
            else
                $('#responsavel-box').css('display', 'none');
       });

       $('.btn-delete-responsavel').on('click', function(e) {
            e.preventDefault();
            if(confirm('Tem certeza que deseja remover ' + $(this).data('responsavel-name') + ' como responsável?')){
                let form_delete_responsavel = $('#form-delete-responsavel');
                let route = '{{route('responsaveis.destroy', ':responsavel')}}';
                route = route.replace(':responsavel', $(this).data('responsavel-id'));
                
                form_delete_responsavel.attr('action', route);
                form_delete_responsavel.submit();
            }
       });

       $('#form-update-sala').validate({
            submitHandler: function(form){
                if($('#aprovacao-sim').is(':checked') && {{count($sala->responsaveis)}} < 1)
                    alert("A sala deve ter ao menos um responsável se necessitar de aprovação para reserva.");
                else
                    form.submit();
            },
            rules: {
                aprovacao: {
                    required: true,
                }
            }
       })
    </script>
@stop
