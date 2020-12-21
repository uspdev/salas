<div class="card">
  <div class="card-header">
    Nova Sala
  </div>
    <div class="card-body">
        <div class="container">
            <div class="row">
                <div class="col-sm form-group">  
                    Nome
                    <br>
                    <input type="text" name="nome" value="{{  old('nome', $sala->nome) }}" >
                </div>
                <div class="col-sm form-group">  
                    Categoria
                    <br>
                    <select name="categoria_id">
                        <option value="" selected="">Selecione uma opção </option>
                        empty($sala::categorias()) ? "" : 
                            @foreach($sala::categorias() as $categoria)
                                <option value="{{ $categoria->id }}" selected=""> {{ $categoria->nome }} </option>
                            @endforeach
                    </select>
                </div>
            </div>
            <div class="row">
                <div class="col-sm form-group">
                    Capacidade<br>
                    <input name="capacidade" type="number" min="0">
                </div>
            </div>
        </div>
        <button type="submit" class="btn btn-success"> Enviar </button>   
    </div>
</div>