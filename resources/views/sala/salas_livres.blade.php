@extends('main')
@section('content')
<div class="card">
  <div class="card-header"><b>Procurar por salas disponíveis</b></div>
    <div class="card-body">
      <form method="GET" action="{{ route('salas.livres') }}" id="form-search">
        @csrf
        <div class="row">
          <div class="form-group">
          <div class="col">
            <label id="dia_do_mes">Data*</label>
            <input type="text" class="datepicker form-control" id="data" type="text" name="data">
            <small class="text-muted">Ex.: {{ $today->format('d/m/Y') }}</small>
          </div>
        </div>
          <div class="form-group">
          <div class="col">
            <label>Horário início*</label>
            <input type="text" name="horario_inicio" id="horario_inicio" class="form-control">
            <small class="text-muted">Formato 9:00</small>
          </div>
          </div>
        <div class="form-group">
          <div class="col">
            <label>Horário fim*</label>
            <input type="text" name="horario_fim" id="horario_fim" class="form-control">
            <small class="text-muted">Formato 9:00</small>
            </div>
          </div>
          <div class="form-group">
            <div class="col">
                <label>Recursos</label>
                <select name="recursos[]" class="select2 form-control" multiple="multiple" style="width:230px;">
                  @foreach(\App\Models\Recurso::all() as $recurso)
                  <option value="{{ $recurso['id'] }}">{{$recurso['nome']}}</option>
                  @endforeach
                </select>
              </div>
          </div>
          <div class="form-group" style="margin-top:-1.5rem;">
            <div class="col">
              <br/>
                <input type="checkbox" name="rep_bool" id="sim" style="width:15px; height:15px;">
                <label>
                  Repetir dias
                </label>
                <div class="checkFlex" style="display:none;">
                    @include('reserva.partials.checkFlex', ['name' => "repeat_days[1]", 'type' => "checkbox", 'value' => "1", 'label' => "Seg"])
                    @include('reserva.partials.checkFlex', ['name' => "repeat_days[2]", 'type' => "checkbox", 'value' => "2", 'label' => "Ter"])
                    @include('reserva.partials.checkFlex', ['name' => "repeat_days[3]", 'type' => "checkbox", 'value' => "3", 'label' => "Qua"])
                    @include('reserva.partials.checkFlex', ['name' => "repeat_days[4]", 'type' => "checkbox", 'value' => "4", 'label' => "Qui"])
                    @include('reserva.partials.checkFlex', ['name' => "repeat_days[5]", 'type' => "checkbox", 'value' => "5", 'label' => "Sex"])
                    @include('reserva.partials.checkFlex', ['name' => "repeat_days[6]", 'type' => "checkbox", 'value' => "6", 'label' => "Sáb"])
                    @include('reserva.partials.checkFlex', ['name' => "repeat_days[0]", 'type' => "checkbox", 'value' => "0", 'label' => "Dom"])
                </div>
              </div>
          </div>
          <div class="form-group" id="repeat_until" style="display:none;">
            <div class="col">
              <label>Repetição semanal até</label>
              <input type="text" class="datepicker form-control" id="data_limite" type="text" name="data_limite">
              <small class="text-muted">Ex.: {{ $today->addDays(7)->format('d/m/Y') }}</small>
            </div>
          </div>
          <div class="form-group" id="btn" style="margin-left:-137px;">
            <div class="col" style="margin-top:30px;">
              <button name="search" id="search" type="submit" class="btn btn-success"><i class="fas fa-search"></i></button>
            </div>
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

      $('#sim').on('click',function(){
        const ativo = this.checked;
        $('.checkFlex, #repeat_until').toggle(ativo);
        $("#btn").css('margin-left','0');
        $('#dia_do_mes').text(ativo ? 'Dia Inicial*' : 'Data*');

        if(!$(this).is(':checked')){
          $('.form-check-input').not(this).prop('checked',false);
          $("#btn").css('margin-left','-137px');
          $('#data_limite').val('');
        }
      });

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
            if(response == ''){
              html += `<div class="alert alert-danger">Nenhuma sala encontrada</div>`;
            }
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
  <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
@endsection
