<div class="card">
    <div class="card-header">
        <b>{{ $title }} Categoria</b>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-sm form-group">  
                <b>Título</b>
                <br>
                <input type="text" class="form-control" name="nome" value="{{  old('nome', $categoria->nome) }}" >
            </div>
        </div>

        <div class="row mt-3">
            <div class="col-sm form-group">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="habilitar_pular_dias_conflito" value="1" id="habilitar_pular_dias_conflito" {{ old('habilitar_pular_dias_conflito', $categoria->habilitar_pular_dias_conflito ?? true) ? 'checked' : '' }}>
                    <label class="form-check-label" for="habilitar_pular_dias_conflito">
                        Habilitar opção de pular dias de conflito
                    </label>
                </div>
            </div>
        </div>

        <button type="submit" class="btn btn-success mt-3"> Enviar </button>   
    </div>
</div>
