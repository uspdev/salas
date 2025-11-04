<form method="get" id="form-search">
        <div class="col-sm-6">
            <label><b>Escolha o prédio</b></label>
            <select id="categoria_id" name="categoria_id[]" class="select2 form-control">
            
                @foreach ($categorias as $categoria)
                    <option value="{{ $categoria->id }}">
                        {{ $categoria->nome }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="col-sm-5">
            <label><b>Escolha a data</b></label>
            <input id="data" type="text" class="datepicker form-control" name="data" value="{{ old('data', request()->data) }}">
            <small class="text-muted">Ex.: {{ $data->format('d/m/Y') }}</small>
        </div>
        <div class="col-sm-1">
            <button type="submit" class="btn btn-success"><i class="fas fa-search"></i></button>
        </div>
    </div>
</form>