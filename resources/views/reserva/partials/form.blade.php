<div class="card">
  <div class="card-header">
    Featured
  </div>
  <div class="card-body">

  <div class="row">
    <div class="col-sm form-group">     
        <label for="" class="required"><b>Nome: </b></label>
        <input type="text" name="nome" value="{{  old('nome', $reserva->nome) }}"> 
    </div>
  </div>

  <div class="row">

    <div class="col-sm form-group">
        <label for="" class="required"><b>Início </b></label>
        <input type="text" name="data_inicio" class="" value="{{  old('data_inicio', $reserva->data_inicio) }}">
    </div>
<br>
    <div class="col-sm form-group">
        <label for="" class="required"><b>Horário Início </b></label>
        <input type="time" name="horario_inicio" value="{{  old('horario_inicio', $reserva->horario_inicio) }}">
    </div>
    <br>
    <div class="col-sm form-group">
        <label for="" class="required"><b>Fim </b></label>
        <input type="text" name="data_fim" class="" value="{{  old('data_fim', $reserva->data_fim) }}">
    </div>
    <br>
    <div class="col-sm form-group">
        <label for="" class="required"><b>Horário Fim </b></label>
        <input type="time" name="horario_fim" value="{{  old('horario_fim', $reserva->horario_fim) }}">
    </div>
    <br>
    <div class="col-sm form-group">
        <label for="" class="required"><b>Cor </b></label>
        <input type="color" name="cor" value="{{  old('cor', $reserva->cor) }}">
    </div>

  </div>


  <div class="row">
    <div class="col-sm form-group">     
        <label for="" class="required"><b>Sala </b></label>
        <input type="text" name="sala_id" value=""> 
    </div>
  </div>

    <button type="submit" class="btn btn-success"> Enviar </button>
  </div>
</div>


