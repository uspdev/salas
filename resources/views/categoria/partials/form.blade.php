<div class="card">
  <div class="card-header">
    <b>Nova Categoria</b>
  </div>
    <div class="card-body">
        <div class="row">
            <div class="col-sm form-group">  
                <b>Nome</b>
                <br>
                <input type="text" class="form-control" name="nome" value="{{  old('nome', $categoria->nome) }}" >
            </div>
        </div>
        <button type="submit" class="btn btn-success"> Enviar </button>   
    </div>
</div>
