@extends('main')
@section('content')
<div class="card">
  <div class="card-header"><b>Procurar por salas disponíveis</b></div>
    <div class="card-body">
      <form method="GET" action="{{ route('salas.livres') }}" id="form-search">
        @csrf
        <div class="row">
          <div class="col">
            <label>Data</label>
            <input type="text" class="datepicker form-control" id="data" type="text" name="data">
            <small class="text-muted">Ex.: {{ $today }}</small>
          </div>
          <div class="col">
            <label>Horário início</label>
            <input type="text" name="horario_inicio" id="horario_inicio" class="form-control">
            <small class="text-muted">Formato 9:00</small>
          </div>
          <div class="col">
            <label>Horário fim</label>
            <input type="text" name="horario_fim" id="horario_fim" class="form-control">
            <small class="text-muted">Formato 9:00</small>
          </div>
          <div class="col" style="margin-top:30px;">
            <button name="search" id="search" type="submit" class="btn btn-success"><i class="fas fa-search"></i></button>
          </div>
        </div>
      </form>
    </div>
  </div>
  <div class="card">
    <div class="card-header"><b>Salas disponíveis</b></div>
      <div class="spinner-border m-5 d-none" role="status" id="spinner">
        <span class="sr-only">Carregando...</span>
      </div>
    <div class="card-body ml-5" id="disponivel">
    </div>
  </div>
</div>
@endsection('content')

@section('javascripts_bottom')
  <script>
    $(document).ready(function(){
      $('#form-search').on('submit', function(e) {
        e.preventDefault()

        $.ajax({
          url: $(this).attr('action'),
          type: $(this).attr('method'),
          data: $(this).serialize(),
          beforeSend: function() {
            $("#disponivel").empty();
            $("#spinner").removeClass('d-none');
          },
          success: function(response) {
            clearMsg();
            var html = '';
            $.each(response, function(index, categoria) {
              html += `<b>${index}</b><ul class="list-unstyled">`;
              $.each(categoria, function(index, item) {
                html += `<li class="ml-5 mt-2"><a href="/salas/${item.id}">${item.nome}</a> - capacidade: ${item.capacidade} pessoas</li>`;
              });
              html += `</ul>`;
            });
            $("#disponivel").append(html);
          },
          error: function(response) {
            clearMsg();
            $(".flash-message").append("<div class='alert alert-danger'><ul></ul></div>");
            $.each(response.responseJSON.errors, function(key, value) {
              $(".alert-danger ul").append('<li>' + value + '</li>');
            });
          }
        });

      });
    });

    function clearMsg() {
      $(".flash-message").html('');
      $("#spinner").addClass('d-none');
    }
  </script>
@endsection
