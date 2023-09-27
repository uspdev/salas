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
        @if ($title == 'Editar')
            <div class="row">
                <div class="col-sm form-group">
                    <b>Necessita de Aprovação?</b>
                    <div class="form-check">
                        <input name="aprovacao" type="radio" class="form-check-input radio-aprovacao" id="aprovacao-sim" value="1" {{$sala->aprovacao ? 'checked' : ''}}>
                        <label for="aprovacao-sim">Sim</label>
                    </div>
                    <div class="form-check">
                        <input name="aprovacao" type="radio" class="form-check-input radio-aprovacao" id="aprovacao-nao" value="0" {{!$sala->aprovacao ? 'checked' : ''}}>
                        <label for="aprovacao-nao">Não</label>
                    </div>
                    <div id="responsavel-box" @if (!$sala->aprovacao) style="display: none" @endif>
                        <b>Responsáveis</b>
                        <div class="d-flex flex-inline form-group">
                            <input name="codpes" type="text" placeholder="Número USP" class="w-25 form-control" id="numero-usp" form="form-add-responsavel" required> 
                            <input name="sala" type="hidden" value="{{$sala->id}}" form="form-add-responsavel">
                            <button class="btn btn-success ml-2" id="btn-inserir-responsavel" type="submit" form="form-add-responsavel">Inserir</button>
                        </div>
                        <div class="card form-group">
                            <div class="card-header">Lista de Responsáveis</div>
                            <ul class="list-group list-group-flush">
                                @if (count($responsaveis) > 0)
                                    @foreach ($responsaveis as $responsavel)
                                        <li class="list-group-item d-inline-flex justify-content-between">{{$responsavel->user->codpes}} - {{$responsavel->user->name}}
                                            <button data-responsavel-name="{{$responsavel->user->name}}" data-responsavel-id="{{$responsavel->id}}" class="btn btn-danger btn-sm btn-delete-responsavel" form="form-delete-responsavel">
                                                <i class="fa fa-trash" ></i>
                                            </button>
                                        </li>
                                    @endforeach
                                @else
                                    <li class="list-group-item">Não há responsáveis cadastrados.</li>
                                @endif
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        @endif
        <button type="submit" class="btn btn-success"> Enviar </button>
        <br>
    </div>
</div>
