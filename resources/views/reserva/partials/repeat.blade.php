<div class="row" id="repeat_container">
    <div class="col-sm form-group">
        <b>Dias da repetição</b>
        <div class="checkFlex">
            @include('reserva.partials.checkFlex', ['name' => "repeat_days[1]", 'type' => "checkbox", 'value' => "1", 'label' => "Seg"])
            @include('reserva.partials.checkFlex', ['name' => "repeat_days[2]", 'type' => "checkbox", 'value' => "2", 'label' => "Ter"])
            @include('reserva.partials.checkFlex', ['name' => "repeat_days[3]", 'type' => "checkbox", 'value' => "3", 'label' => "Qua"])
            @include('reserva.partials.checkFlex', ['name' => "repeat_days[4]", 'type' => "checkbox", 'value' => "4", 'label' => "Qui"])
            @include('reserva.partials.checkFlex', ['name' => "repeat_days[5]", 'type' => "checkbox", 'value' => "5", 'label' => "Sex"])
            @include('reserva.partials.checkFlex', ['name' => "repeat_days[6]", 'type' => "checkbox", 'value' => "6", 'label' => "Sáb"])
            @include('reserva.partials.checkFlex', ['name' => "repeat_days[7]", 'type' => "checkbox", 'value' => "7", 'label' => "Dom"])
        </div>
        <small class="form-text text-muted">Selecione os dias da semana que o evento deve se repetir.</small>
    </div>
    <div class="col-sm form-group">
        <label for="" class="required"><b> Repetição semanal até:</b></label>
        <br>
        <input type="text" class="datepicker" id="repFormControl"name="repeat_until" value="{{  old('repeat_until', $reserva->repeat_until) }}">
        <small class="form-text text-muted">Formato: 30/12/2021</small>
    </div>
</div>
