<div class="card">
    <div class="card">
        <div class="card-header">
            Período Letivo
        </div>
        <div class="card-body">
            <form method="POST" action="/periodos_letivos">
                @csrf
                <div class="form-group row">
                    <label for="codigo" class="col-sm-3 col-form-label">Código</label>
                    <div class="col-sm-9">
                        <input class="form-control" type="text" name="codigo" id="txt_codigo"
                            value="{{ old('codigo', $periodo->codigo) }}" placeholder="ex: 20231" 
                            required style="width: 200px;">
                    </div>
                </div>
                <div class="form-group row">
                    <label for="data" class="col-sm-3 col-form-label">Data de início do período letivo</label>
                    <div class="col-sm-9">
                        <input type="date" name="data_inicio" id="txt_data_inicio"
                            value="{{ old('data_inicio', $periodo->data_inicio) }}" required>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="data" class="col-sm-3 col-form-label">Data de término do período letivo</label>
                    <div class="col-sm-9">
                        <input type="date" name="data_fim" id="txt_data_fim"
                            value="{{ old('data_fim', $periodo->data_fim) }}" required>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="data" class="col-sm-3 col-form-label">Data início das reservas para o período letivo</label>
                    <div class="col-sm-9">
                        <input type="date" name="data_inicio_reservas" id="txt_data_inicio_reservas"
                            value="{{ old('data_inicio_reservas', $periodo->data_inicio_reservas) }}" required>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="data" class="col-sm-3 col-form-label">Data término das reservas para o período letivo</label>
                    <div class="col-sm-9">
                        <input type="date" name="data_fim_reservas" id="txt_data_fim_reservas"
                            value="{{ old('data_fim_reservas', $periodo->data_fim_reservas) }}" required>
                    </div>
                </div>
                <div class="col-12 mb-3">
                    <button type="submit" class="btn btn-success">Enviar</button>
                                   
                <a class="btn btn-secondary" href="/periodos_letivos" role="button"
            data-bs-toggle="tooltip" title="Cancelar" >
            Cancelar
</a>
                </div>
            </form>
        </div>
    </div>

</div>
