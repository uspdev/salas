<div class="card">
  <div class="card-header">
    <h5>Nova reserva</h5>
  </div>
    <div class="card-body">
      <div class="container">
        <div class="row">
          <div class="col-sm form-group">     
            <label for="" class="required"><b>Nome</b></label>
            <br>
            <input type="text" name="nome" value="{{  old('nome', $reserva->nome) }}"> 
          </div>
        </div>
        <div class="row">
          <div class="col-sm form-group">
            <label for="" class="required"><b>Data de início </b></label>
            <br>
            <input type="text" name="data_inicio" class="datepicker" value="{{  old('data_inicio', $reserva->data_inicio) }}">
          </div>
          <div class="col-sm form-group">
            <label for="" class="required"><b>Data de fim </b></label>
            <br>
            <input type="text" name="data_fim" class="datepicker" value="{{  old('data_fim', $reserva->data_fim) }}">
          </div>
          <div class="col-sm form-group">
            <label for="" class="required"><b>Evento de dia inteiro?</b></label>
            <br>
            <input type="radio" name="full_day_event" value="1" @if (isset($reserva->full_day_event) and ($reserva->full_day_event === 1))
                checked
            @elseif ((old('full_day_event') != null) and (old('fixarip') == 1))
                checked
            @endif> Sim<br>
            <input type="radio" name="full_day_event" value="0" @if (isset($reserva->full_day_event) and ($reserva->full_day_event === 0))
                            checked
                        @elseif ((old('full_day_event') != null) and (old('fixarip') == 0))
                            checked
            @endif> Não
          </div>
        </div>
        <div class="row">
          <div class="col-sm form-group">
            <label for="" class="required"><b>Horário de início </b></label>
            <br>
            <input type="time" name="horario_inicio" value="{{  old('horario_inicio', $reserva->horario_inicio) }}">
            <br>
            <small class="form-text text-muted">Formato: 24:00 </small>
          </div>        
          <div class="col-sm form-group">
            <label for="" class="required"><b>Horário de fim </b></label>
            <br>
            <input type="time" name="horario_fim" value="{{  old('horario_fim', $reserva->horario_fim) }}">
            <br>
            <small class="form-text text-muted">Formato: 24:00 </small>
          </div>
        </div>
        <div class="row">
          <div class="col-sm form-group">     
            <label for="" class="required"><b>Sala </b></label>
            <br>
            <select name="sala_id">
                        <option value="" selected="">Selecione uma opção </option>
                        empty($reserva::salas()) ? "" : 
                            @foreach($reserva::salas() as $sala)
                                <option value="{{ $sala->id }}" selected=""> {{ $sala->nome }} </option>
                            @endforeach
            </select>
          </div>
          <div class="col-sm form-group">
            <label for="" class="required"><b>Cor</b></label>
            <br>
            <input type="color" name="cor" value= "{{ empty($reserva->cor) ? '#ff0000' :  old('cor', $reserva->cor) }}">
          </div>
        </div>
        <div class="row">
          <div class="col-sm form-group">
            <label for="" class="required"><b>Descrição</b></label>
            <br>
            <textarea name="descricao" class="form-control" rows="3">{{  old('descricao', $reserva->descricao) }}</textarea>
            <br>
          </div>
        </div>
    </div>
    <br>
    <button type="submit" class="btn btn-success"> Enviar </button>
  </div>
</div>




