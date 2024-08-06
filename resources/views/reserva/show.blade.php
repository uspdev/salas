@extends('main')
@section('content')
  @include('reserva.partials.fields')
@endsection  

@section('javascripts_bottom')
<script>
  $(document).ready(function(){
    $('.btn-purge').on('click', function(e) {
      let purge = $(e.target).data('purge');
      $('#purge').val(purge);
    });
  });
</script>
@endsection