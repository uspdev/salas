<div class="card">
    <div class="card-header">
        <h5>Novo Recurso</h5>
    </div>
    <div class="card-body">
        <div class="container">
            <div class="row">
                <div class="col-sm form-group">  
                    <b>Nome</b>
                    <br>
                    <input type="text" name="nome" value="{{  old('nome', $recurso->nome) }}" >
                </div>
            </div>
        </div>
        <button type="submit" class="btn btn-success"> Enviar </button>   
    </div>
</div>