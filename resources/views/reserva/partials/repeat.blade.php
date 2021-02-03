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