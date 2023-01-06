<div class="card">
    <div class="card-header">
        <b>{{ $title }} Sala</b>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-sm form-group">  
                <b>Nome</b>
                <br>
                <input class="form-control" type="text" name="nome" value="{{  old('nome', $sala->nome) }}" >
            </div>
            <div class="col-sm form-group">
                <b>Capacidade</b>
                <br>
                <input name="capacidade" class="form-control" type="number" min="0" value="{{  old('capacidade', $sala->capacidade) }}">
            </div>
            <div class="col-sm form-group">  
                <b>Categoria</b>
                <br>
                <select class="categorias_select" name="categoria_id" style="width: 100%;">
                    <option value="" selected>Selecione uma opção </option>
                        @foreach($categorias as $categoria)
                            @if (old('categoria_id') == '')
                                <option value="{{ $categoria->id }}" {{ ($sala->categoria_id == $categoria->id) ? 'selected' : ''}}>
                                    {{ $categoria->nome }}
                                 </option>
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
                <b>Recursos</b>
                <br>
                <table>
                    @foreach($recursos as $recurso)
                        <tr>
                            <td><input {{ $recurso->checked ? 'checked' : null }} type="checkbox" class="recurso" name="recursos[]" value="{{ $recurso->id }}"></td>
                            <td>{{ $recurso->nome }}</td>
                        </tr>
                    @endforeach
                </table>
            </div>
            
        </div>
        <button type="submit" class="btn btn-success"> Enviar </button>
        <br>
    </div>
</div>
