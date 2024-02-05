@if ($reserva->parent_id != null and ($reserva->parent_id != $reserva->id))
    <div class="alert alert-danger" role="alert">
        Atenção: Esta reserva faz parte de um grupo e você está editando somente esta instância!<br><br>
        Se deseja editar todas reservas do grupo simultaneamente
        <a href="/reservas/{{ $reserva->parent_id }}/edit">clique aqui</a>
    </div>
@endif

@if ($reserva->parent_id != null and ($reserva->parent_id == $reserva->id))
    <div class="alert alert-danger" role="alert">
        Atenção: Você está editando um grupo de reservas simultaneamente!
    </div>
@endif

<div class="card">
    <div class="card-header">
        <b>{{ $title }}</b>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-sm form-group">
                <label for="" class="required"><b>Título</b></label>
                <br>
                <input type="text" class="form-control"  name="nome" value="{{  old('nome', $reserva->nome) }}">
            </div>
            <div class="col-sm form-group">
                <label for="" class="required"><b>Finalidade</b></label>
                <br>
                <select name="finalidade_id" class="form-control form-select" required>
                    @foreach ($finalidades as $finalidade)
                        <option value="{{$finalidade->id}}">{{$finalidade->legenda}}</option>
                    @endforeach
                </select>
            </div>
        </div>

        @include('reserva.partials.datetime-fields')

        <div class="row">
            <div class="col-sm form-group">
                <b>Responsáveis pela reserva</b>
                <div class="mt-2">
                    <div class="form-check form-check-inline">
                        <input id="eu" value="eu" class="form-check-input radio-eu" type="radio" name="tipo_responsaveis" role="button" {{old('tipo_responsaveis', $reserva->tipo_responsaveis) == 'eu' ? 'checked' : ''}}>
                        <label for="eu" class="form-check-label radio-eu" role="button">Eu</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input id="unidade" value="unidade" class="form-check-input" type="radio" name="tipo_responsaveis" role="button" {{old('tipo_responsaveis', $reserva->tipo_responsaveis) == 'unidade' ? 'checked' : ''}}>
                        <label for="unidade" class="form-check-label" role="button">Pessoas da unidade</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input id="externo" value="externo" class="form-check-input" type="radio" name="tipo_responsaveis" role="button" {{old('tipo_responsaveis', $reserva->tipo_responsaveis) == 'externo' ? 'checked' : ''}}>
                        <label for="externo" class="form-check-label" role="button">Pessoas externas</label>
                    </div>
                </div>
            </div>
        </div>
        <div class="add-responsavel-unidade mb-3">
            <select name="responsaveis_unidade[]" class="form-control form-control-sm" multiple="multiple" data-placeholder="Buscar pessoa">
                @if ($reserva->tipo_responsaveis == 'unidade')
                    @foreach ($reserva->responsaveis as $responsavel)
                        <option value="{{$responsavel->codpes}}" selected="seleted">{{$responsavel->codpes}} {{$responsavel->nome}}</option>   
                    @endforeach
                @endif
            </select>
        </div>

        <div class="add-responsavel-externo mb-3">
            @if ($reserva->tipo_responsaveis == 'externo')
                @foreach ($reserva->responsaveis as $responsavel)
                    <input type="text" name="responsaveis_externo[]" class="form-control mb-2" placeholder="Nome completo do responsável..." value="{{$responsavel->nome}}">
                @endforeach
                <a class="btn btn-primary btn-sm" id="btn-add-responsavel-externo-input" @if(count($reserva->responsaveis) > 2) style="display: none" @endif><i class="fas fa-plus-circle"></i> Adicionar mais um responsável</a>
            @else
                <input type="text" name="responsaveis_externo[]" class="form-control mb-2" placeholder="Nome completo do responsável..." value="">
                <a class="btn btn-primary btn-sm" id="btn-add-responsavel-externo-input"><i class="fas fa-plus-circle"></i> Adicionar mais um responsável</a>
            @endif

            <a class="btn btn-secondary btn-sm" id="btn-limpar-responsaveis-externo-input"><i class="fas fa-eraser"></i> Apagar responsáveis</a>
        </div>

        <div class="row">
            <div class="col-sm form-group">
                <label for="" class="required"><b>Descrição</b></label>
                <br>
                <textarea name="descricao" class="form-control" rows="3">{{  old('descricao', $reserva->descricao) }}</textarea>
                <br>
            </div>
        </div>
        <button id="b-reservas" type="submit" class="btn btn-success">Enviar</button>
    </div>
</div>
