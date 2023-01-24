<div class="card">
    <div class="card-header">
        <b>{{ $title }}</b>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-sm form-group">
                <label for="" class="required"><b>Título</b></label>
                <br>
                <input type="text" class="form-control"  name="nome" value="{{  old('nome', $reserva->nome) }}">
            </div>
            <div class="col-sm form-group">
                <label for="" class="required"><b>Cor</b></label>
                <br>
                <input type="color" class="form-control form-control-color" name="cor" value= "{{ empty($reserva->cor) ? $settings->cor : old('cor', $reserva->cor) }}">
            </div>
        </div>

        @if($editOne)
            @include('reserva.partials.datetime-fields')
        @endif

        <div class="row">
            <div class="col-sm form-group">
                <label for="" class="required"><b>Descrição</b></label>
                <br>
                <textarea name="descricao" class="form-control" rows="3">{{  old('descricao', $reserva->descricao) }}</textarea>
                <br>
            </div>
        </div>
        <button id="b-reservas" type="submit" class="btn btn-success">Enviar</button>
    </div>
</div>
