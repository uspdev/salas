<div class="card">
    <div class="card-header">
        Adicionar Período Letivo
    </div>
    <div class="card-body">
        <form method="POST" action="/periodos_letivos">
            @csrf
            <div class="row justify-content-around">
                <div class="col-sm form-group">
                    <label for="codigo">Código</label>
                    <input class="form-control" type="text" name="codigo" id="txt_codigo" value="{{  old('codigo') }}" placeholder="ex: 20231" maxlength="5" required>
                </div>
                <div class="col-sm form-group">
                    <label for="data">Data de início</label>
                    <input type="date" name="data_inicio" id="txt_data_inicio" value="{{ old('data_inicio') }}" required>
                </div>
                <div class="col-sm form-group">
                    <label for="data">Data de término</label>
                    <input type="date" name="data_fim" id="txt_data_fim" value="{{ old('data_fim') }}" required>
                </div>
                <div class="col-sm form-group">
                    <label for="data">Data início das reservas</label>
                    <input type="date" name="data_inicio_reservas" id="txt_data_inicio_reservas" value="{{ old('data') }}" required>
                </div>
                <div class="col-sm form-group">
                    <label for="data">Data término das reservas</label>
                    <input type="date" name="data_fim_reservas" id="txt_data_fim_reservas" value="{{ old('data_fim_reservas') }}" required>
                </div>
                <div class="col-1">
                    <button type="submit" class="btn btn-success"> Enviar </button> 
                </div>
            </div>    
        </form>      
    </div>
</div>