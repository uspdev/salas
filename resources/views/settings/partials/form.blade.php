<div class="card">
    <div class="card-header">
        <b>Configurações Gerais</b>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-sm form-group">
                <label for="" class="required"><b>Escolha a cor padrão para reservas</b></label>
                <br>
                <input type="color" class="form-control form-control-color" name="cor" value= "{{ old('cor', $cor) }}">
            </div>
        </div>
        <div class="row">
            <button type="submit" class="btn btn-success"> Enviar </button>
        </div>
    </div>
</div>