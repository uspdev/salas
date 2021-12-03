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
                    <option value="" selected>Selecione uma opção </option>
                    empty($categorias ? "" : 
                        @foreach($categorias as $categoria)
                            {{-- 1. Situação em que não houve tentativa de submissão --}}
                            @if (old('categoria_id') == '')
                                <option value="{{ $categoria->id }}" {{ ($sala->categoria_id == $categoria->id) ? 'selected' : ''}}>
                                    {{ $categoria->nome }}
                                 </option>
                            {{-- 2. Situação em que houve tentativa de submissão, o valor de old prevalece --}}
                            @else
                                <option value="{{ $categoria->id }}" {{ (old('categoria_id') == $sala->id) ? 'selected' : ''}}>
                                    {{ $categoria->nome }}
                                </option>
                            @endif
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
        <a class="btn btn-outline-dark" href="/salas" role="button">
            <i class="fas fa-arrow-left"></i> Voltar
        </a><br>
    </div>
</div>
