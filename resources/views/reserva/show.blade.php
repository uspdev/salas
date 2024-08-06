@extends('main')
@section('content')
  @include('reserva.partials.fields')
@endsection  

@section('javascripts_bottom')
<script>
  $(document).ready(function(){
    $('.btn-purge').on('click', function(e) {
      $('#purge').val($(e.target).data('purge')); // Atribui o valor 1 para o input de id 'purge', com base no botão clicado no questionamento de exclusão.
      $('#form-excluir').submit();
    });

    $('#btn-excluir').click(function(){
      $('#form-excluir').submit();
    });
  });
</script>
@endsection