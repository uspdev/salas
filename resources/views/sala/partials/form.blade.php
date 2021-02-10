<div class="card">
  <div class="card-header">
    <b>Nova Sala</b>
  </div>
    <div class="card-body">
        <div class="row">
            <div class="col-sm form-group">  
                <b>Nome</b>
                <br>
                <input class="form-control" type="text" name="nome" value="{{  old('nome', $sala->nome) }}" >
            </div>
            <div class="col-sm form-group">  
                <b>Categoria</b>
                <br>
                <select class="form-select" name="categoria_id">
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
                <b>Capacidade</b><br>
                <input name="capacidade" type="number" min="0" value="{{  old('capacidade', $sala->capacidade) }}">
            </div>
        </div>
        <button type="submit" class="btn btn-success"> Enviar </button>   
    </div>
</div>