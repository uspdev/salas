<div class="row">
    <div class="col-sm form-group">
        <b>Repetição</b>
        <ul class="list-group">
            <li class="list-group-item">
                <input type="checkbox" name="repeat_days[]" value="0"> Dom
            </li>
            <li class="list-group-item">
                <input type="checkbox" name="repeat_days[]" value="1"> Seg
            </li>
            <li class="list-group-item">
                <input type="checkbox" name="repeat_days[]" value="2"> Ter
            </li>
            <li class="list-group-item">
                <input type="checkbox" name="repeat_days[]" value="3"> Qua
            </li>
            <li class="list-group-item">
                <input type="checkbox" name="repeat_days[]" value="4"> Qui
            </li>
            <li class="list-group-item">
                <input type="checkbox" name="repeat_days[]" value="5"> Sex
            </li>
            <li class="list-group-item">
                <input type="checkbox" name="repeat_days[]" value="6"> Sáb
            </li>
            <small class="form-text text-muted">Selecione os dias da semana que o evento deve se repetir.</small>
        </ul>
    </div>

    <div class="col-sm form-group">
        <label for="" class="required"><b> Repetição semanal até:</b></label>
        <br>
        <input type="text" class="form-control" name="repeat_until" value="{{  old('repeat_until', $reserva->repeat_until) }}">
        <small class="form-text text-muted">Formato: 30/12/2021</small>
    </div>
</div>