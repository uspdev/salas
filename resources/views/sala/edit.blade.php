@extends('main')
@section('content')
    <form action="{{route('responsaveis.store')}}" method="POST" id='form-add-responsavel'>@csrf</form>
    <form method="POST" id="form-delete-responsavel">@csrf @method('DELETE')</form>
    <form method="POST" action="/salas/{{ $sala->id }}">
        @csrf
        @method('patch')
        @include('sala.partials.form', ['title' => "Editar"])
    </form>
@endsection

@section('javascripts_bottom')
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

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

       $('#btn-delete-responsavel').on('click', function(e) {
            e.preventDefault();
            let form_delete_responsavel = $('#form-delete-responsavel');
            let route = '{{route('responsaveis.destroy', ':responsavel')}}';
            route = route.replace(':responsavel', $(this).data('responsavel'));
            
            form_delete_responsavel.attr('action', route);
            form_delete_responsavel.submit();
       });
    </script>
@stop
