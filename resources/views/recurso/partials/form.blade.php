<div class="card">
    <div class="card-header">
        <b>{{$title}} Recurso</b>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-sm form-group">
                <b>Nome</b>
                <br>
                <input class="form-control" type="text" name="nome" value="{{  old('nome', $recurso->nome) }}" >
            </div>
        </div>
        <button type="submit" class="btn btn-success"> Enviar </button>
    </div>
</div>