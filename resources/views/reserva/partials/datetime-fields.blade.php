<div class="row">
    <div class="col-sm form-group">
        <label for="" class="required" ><b>Data</b></label>
        <br>
        <input type="text" name="data" class="datepicker" value="{{ $_GET["data"] ?? old('data', $reserva->data) }}">
    </div>
    <div class="col-sm form-group">
        <label for="" class="required"><b>Horário de início </b></label>
        <br>
        <input class="form-control" type="text" name="horario_inicio" value="{{ $_GET["start"] ?? old('horario_inicio', $reserva->horario_inicio) }}">
        <small class="form-text text-muted">Formato: 9:00 </small>
    </div>
    <div class="col-sm form-group">
        <label for="" class="required"><b>Horário de fim </b></label>
        <br>
        <input class="form-control" type="text" name="horario_fim" value="{{ $_GET["end"] ?? old('horario_fim', $reserva->horario_fim) }}">
        <small class="form-text text-muted">Formato: 9:00 </small>
    </div>
    <div class="col-sm form-group">
        <label for="" class="required"><b>Sala </b></label>
        <br>
        <select id="salas_select" class="form-control" name="sala_id" onchange="salaChanged(this.value)">
        @foreach($categorias as $categoria)
            <optgroup label="{{ $categoria->nome }}">
              @foreach($categoria->salas as $sala)
              @if (Gate::allows('members', $sala->id))
                @if( old('sala_id') == '' and isset($reserva->sala_id) )
                  <option value="{{ $sala->id }}" {{ ($reserva->sala_id ==
                  $sala->id) || (isset($_GET['sala']) && $_GET['sala'] == $sala->id) ? 'selected' : '' }} >{{ $sala->nome }} [ Capacidade: {{
                    $sala->capacidade }} ]
                      @forelse($sala->recursos as $recurso)
                        @if ($loop->first)
                          [ Recurso: {{ $recurso->nome }}
                        @else
                          - {{ $recurso->nome }}
                        @endif
                        @if ($loop->last)
                          ]
                        @endif
                      @empty
                      @endforelse
                  </option>
                @else
                  <option value="{{ $sala->id }}"
                    {{ ( old('sala_id') == $sala->id ) || (isset($_GET['sala']) && $_GET['sala'] == $sala->id) ? 'selected' : '' }}>
                    {{ $sala->nome }} [ Capacidade: {{ $sala->capacidade }} ]
                      @forelse($sala->recursos as $recurso)
                        @if ($loop->first)
                          [ Recurso: {{ $recurso->nome }}
                        @else
                          - {{ $recurso->nome }}
                        @endif
                        @if ($loop->last)
                          ]
                        @endif
                      @empty
                      @endforelse
                  </option>
                @endif
              @endif
              @endforeach
            </optgroup>
        @endforeach
        </select>
    </div>
</div>

<div class="row">
    <div class="col-sm form-group">
        @include('reserva.partials.instrucoes-da-sala-badge')
        @include('reserva.partials.instrucoes-da-sala')
    </div>
</div>

@if($reserva->id == null or ($reserva->parent_id != null and ($reserva->id == $reserva->parent_id )))
    <div class="row">
        <div class="col-sm form-group">
            <b>Repetição</b>
            <div class="checkFlex">
                <div class="card">
                    <div class="card-body">
                        <input class="form-check-input me-1" type="radio" value="Não" id="rep_bool_Nao" name="rep_bool"
                            @if (old('rep_bool') == 'Não') checked @endif>
                            <label for="rep_bool_Nao">Não</label>
                    </div>
                </div>
                <div class="card">
                    <div class="card-body">
                        <input class="form-check-input me-1" type="radio" value="Sim" id="rep_bool_Sim" name="rep_bool"
                          @if (old('rep_bool') == 'Sim' or ($reserva->parent_id != null and ($reserva->id == $reserva->parent_id ))) checked @endif>
                          <label for="rep_bool_Sim">Sim</label>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @include('reserva.partials.repeat')
@endif

<script type="text/javascript">
    var categorias = @json($categorias);

    $(document).ready(function() {
        salaChanged($('#salas_select').val());
    });

    function salaChanged(sala_id) {
        var sala = categorias.flatMap(function (categoria) {
            return categoria.salas;
        }).find(function (sala) {
            return sala.id == sala_id;
        });

        if (sala?.instrucoes_reserva) {
            $('#instrucoes_badge').css('display', 'block');
            $('#instrucoes').addClass('show');
            $('#instrucoes_reserva').html(sala?.instrucoes_reserva.replace(/\n/g, '<br>'));
        } else {
            $('#instrucoes_badge').css('display', 'none');
            $('#instrucoes').removeClass('show');
            $('#instrucoes_reserva').html('');
        }

        if (sala?.aceite_reserva) {
            $('#aceite_reserva').css('display', 'block');
            $('#checkbox_aceite_reserva').attr('required', true);
            $('#label_aceite_reserva').text(sala?.aceite_reserva.replace(/\n/g, '<br>'));
        } else {
            $('#aceite_reserva').css('display', 'none');
            $('#checkbox_aceite_reserva').attr('required', false);
            $('#label_aceite_reserva').text('');
        }
    }
</script>
