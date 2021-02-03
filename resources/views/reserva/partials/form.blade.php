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
            <label for="" class="required"><b>Data</b></label>
            <br>
            <input type="text" name="data" class="datepicker" value="{{  old('data', $reserva->data) }}">
          </div>
        </div>
        
        <div class="row">
          <div class="col-sm form-group">
            <label for="" class="required"><b>Horário de início </b></label>
            <br>
            <input type="text" name="horario_inicio" value="{{ old('horario_inicio', $reserva->horario_inicio) }}">
            <br>
            <small class="form-text text-muted">Formato: 11:00 </small>
          </div>        
          <div class="col-sm form-group">
            <label for="" class="required"><b>Horário de fim </b></label>
            <br>
            <input type="text" name="horario_fim" value="{{ old('horario_fim', $reserva->horario_fim) }}">
            <br>
            <small class="form-text text-muted">Formato: 15:00 </small>
          </div>
        </div>

        <div class="row">
          
          <div class="col-sm form-group">
             <input type="checkbox" name="repeat_days[]" value="0"> dom
             <input type="checkbox" name="repeat_days[]" value="1"> seg
             <input type="checkbox" name="repeat_days[]" value="2"> ter
             <input type="checkbox" name="repeat_days[]" value="3"> qua
             <input type="checkbox" name="repeat_days[]" value="4"> qui
             <input type="checkbox" name="repeat_days[]" value="5"> sex
             <input type="checkbox" name="repeat_days[]" value="6"> sab
            <small class="form-text text-muted">Selecione os dias da semana que o evento deve se repetir </small>
          </div>

          <div class="col-sm form-group">
            <label for="" class="required"><b> Repetição semanal até:</b></label>
            <br>
            <input type="text" name="repeat_until" value="{{  old('repeat_until', $reserva->repeat_until) }}">
            <br>
            <small class="form-text text-muted">Formato: 30/12/2021</small>
          </div>
        </div>
        
        <div class="row">
          <div class="col-sm form-group">     
            <label for="" class="required"><b>Sala </b></label>
            <br>

            <select name="sala_id">
                <option value="" selected=""> - Selecione  -</option>
                @foreach ($reserva::salas() as $sala)
                    {{-- 1. Situação em que não houve tentativa de submissão --}}
                    @if (old('sala_id') == '')
                      <option value="{{ $sala->id }}" {{ ( $reserva->sala_id == $sala->id) ? 'selected' : ''}}>
                        {{ $sala->nome }}
                      </option>
                    {{-- 2. Situação em que houve tentativa de submissão, o valor de old prevalece --}}
                    @else
                      <option value="{{ $sala->id }}" {{ ( old('sala_id') == $sala->id) ? 'selected' : ''}}>
                        {{ $sala->nome }}
                      </option>
                    @endif
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




