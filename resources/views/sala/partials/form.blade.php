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
                            <td><input dusk={{ "recurso_" . $recurso->id}} {{ $recurso->checked ? 'checked' : null }} type="checkbox" class="recurso" name="recursos[]" value="{{ $recurso->id }}"></td>
                            <td>{{ $recurso->nome }}</td>
                        </tr>
                    @endforeach
                </table>
            </div>

        </div>
        @if ($title == 'Editar')
            <div class="row">
                <div class="col-sm form-group">
                    <b>Necessita de aprovação?</b>
                    <div class="form-check">
                        <input name="aprovacao" type="radio" class="form-check-input radio-aprovacao" id="aprovacao-sim" value="1" {{$sala->restricao->aprovacao ? 'checked' : ''}} onchange="aprovacaoChanged()">
                        <label for="aprovacao-sim">Sim</label>
                    </div>
                    <div class="form-check">
                        <input name="aprovacao" type="radio" class="form-check-input radio-aprovacao" id="aprovacao-nao" value="0" {{!$sala->restricao->aprovacao ? 'checked' : ''}} onchange="aprovacaoChanged()">
                        <label for="aprovacao-nao">Não</label>
                    </div>
                </div>
                <div id="exige_justificativa_recusa_sim_nao" class="col-sm form-group">
                    <b>Exigir justificativa no ato da recusa?</b>
                    <div class="form-check">
                        <input name="exige_justificativa_recusa" type="radio" class="form-check-input radio-exige-justificativa-recusa" id="exige-justificativa-recusa-sim" value="1" {{ ((float) ($sala->restricao->exige_justificativa_recusa ?? 0) > 0) ? 'checked' : '' }}>
                        <label for="exige-justificativa-recusa-sim">Sim</label>
                    </div>
                    <div class="form-check">
                        <input name="exige_justificativa_recusa" type="radio" class="form-check-input radio-exige-justificativa-recusa" id="exige-justificativa-recusa-nao" value="0" {{ ((float) ($sala->restricao->exige_justificativa_recusa ?? 0) == 0) ? 'checked' : '' }}>
                        <label for="exige-justificativa-recusa-nao">Não</label>
                    </div>
                </div>
                <div id="prazo_aprovacao_sim_nao" class="col-sm form-group">
                    <b>Aprovar automaticamente após certo prazo sem aprovação nem rejeição?</b>
                    <div class="form-check">
                        <input name="aprovacao_prazo" type="radio" class="form-check-input radio-aprovacao-prazo" id="aprovacao-prazo-sim" value="1" {{ ((float) ($sala->restricao->prazo_aprovacao ?? 0) > 0) ? 'checked' : '' }} onchange="aprovacaoPrazoChanged()">
                        <label for="aprovacao-prazo-sim">Sim</label>
                    </div>
                    <div class="form-check">
                        <input name="aprovacao_prazo" type="radio" class="form-check-input radio-aprovacao-prazo" id="aprovacao-prazo-nao" value="0" {{ ((float) ($sala->restricao->prazo_aprovacao ?? 0) == 0) ? 'checked' : '' }} onchange="aprovacaoPrazoChanged()">
                        <label for="aprovacao-prazo-nao">Não</label>
                    </div>
                </div>
                <div class="col-sm form-group">
                    <div id="prazo_aprovacao_prazo">
                        <b>Prazo (dias úteis)</b>
                        <br>
                        <input id="number_prazo_aprovacao" name="prazo_aprovacao" class="form-control" type="number" min="0" value="{{ old('prazo_aprovacao', $sala->restricao->prazo_aprovacao) }}">
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm form-group">
                    <div id="responsavel-box" @if (!$sala->restricao->aprovacao) style="display: none" @endif>
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
                                        <li class="list-group-item d-inline-flex justify-content-between">{{$responsavel->codpes}} - {{$responsavel->name}}
                                            <button data-responsavel-name="{{$responsavel->name}}" data-responsavel-id="{{$responsavel->pivot->id}}" class="btn btn-danger btn-sm btn-delete-responsavel" form="form-delete-responsavel">
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

            <div class="row">
                <div class="col-sm form-group">
                    <b>Instruções para Reserva</b>
                    <br>
                    <textarea class="form-control" type="text" name="instrucoes_reserva">{{ old('instrucoes_reserva', $sala->instrucoes_reserva) }}</textarea>
                </div>
                <div class="col-sm form-group">
                    <b>Aceite para Reserva</b>
                    <br>
                    <textarea class="form-control" type="text" name="aceite_reserva">{{ old('aceite_reserva', $sala->aceite_reserva) }}</textarea>
                </div>
            </div>

            <!-- início das restrições -->
            <p><b>Restrições</b>
                <br>
                As salas podem conter algumas restrições de datas para as reservas. A ativação de qualquer uma dessas opções altera o funcionamento do sistema, especialmente nas novas solicitações.
            </p>

            <div class="row">

                <div class="col-sm form-group">
                    <b>Sala bloqueada</b>
                    <br>Impede novas reservas na sala. Essa condição pode ser útil em caso de manutenção ou qualquer outra situação em que a sala não pode ser reservada.


                    <div class="form-check">
                        <input name="bloqueada" type="radio" class="form-check-input radio-bloqueada" id="bloqueada-sim" value="1" {{$sala->restricao->bloqueada ? 'checked' : ''}}>
                        <label for="bloqueada-sim">Sim</label>
                    </div>

                    <div class="form-check">
                        <input name="bloqueada" type="radio" class="form-check-input radio-bloqueada" id="bloqueada-nao" value="0" {{!$sala->restricao->bloqueada ? 'checked' : ''}}>
                        <label for="bloqueada-nao">Não</label>
                    </div>

                    <div class="col-sm form-group" id="box_motivo_bloqueio" @if (old('bloqueada', !$sala->restricao->bloqueada)) style="display: none" @endif>
                        <b>Motivo do bloqueio</b>
                        <br>Descreva resumidamente o motivo, período e outras informações relevantes sobre o bloqueio
                        <input class="form-control" type="text" name="motivo_bloqueio" id="txt_motivo_bloqueio" value="{{  old('motivo_bloqueio', $sala->restricao->motivo_bloqueio) }}" >
                    </div>

                </div>
            </div>

            <div class="row">
                <div class="col-sm form-group">
                    <b>Antecedência mínima</b>
                    <br>Quantidade de dias de antecedência mínima para reservar a sala. Ao habilitar essa opção, o sistema não permite a reserva que não respeite o período mínimo de antecedência, mesmo que a sala esteja livre.
                    <div class="form-group">
                        <label for="dias_antecedencia">Dias de antecedência:</label>
                        <input type="number" name="dias_antecedencia" value="{{ old('dias_antecedencia', $sala->restricao->dias_antecedencia) }}" min="0" max="99999">
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-sm form-group">
                    <b>Duração das reservas</b>
                    <br>As reservas para a sala podem ser limitadas a uma duração mínima e a uma duração máxima. Ao definir os valores, o sistema não permite a reserva da sala além da duração definida e nem com o tempo
                    inferior ao tempo mínimo estipulado, mesmo que a sala esteja livre.

                    <div class="form-group">
                        <label for="dias_antecedencia">Duração mínima:</label>
                        <input type="number" name="duracao_minima" value="{{ old('duracao_minima', $sala->restricao->duracao_minima) }}" min="0" size="5"> minutos
                    </div>
                    <div class="form-group">
                        <label for="dias_antecedencia">Duração máxima:</label>
                        <input type="number" name="duracao_maxima" value="{{ old('duracao_maxima', $sala->restricao->duracao_maxima) }}" min="1" size="5"> minutos
                    </div>
                </div>
            </div>


            <div class="row">
                <div class="col-sm form-group">
                    <b>Limites de datas para as reservas</b>
                        <div class="form-group">
                            <label for="select_tipo_restricao">Escolha uma opção:</label>

                            <select name="tipo_restricao" id="select_tipo_restricao">
                                <option value="NENHUMA" @if(old('tipo_restricao', $sala->restricao->tipo_restricao) === 'NENHUMA') selected @endif>Nenhuma</option>
                                <option value="AUTO" @if(old('tipo_restricao', $sala->restricao->tipo_restricao) === 'AUTO') selected @endif>Data limite dinâmica</option>
                                <option value="FIXA" @if(old('tipo_restricao', $sala->restricao->tipo_restricao) === 'FIXA') selected @endif>Data limite fixa</option>
                                <option value="PERIODO_LETIVO" @if(old('tipo_restricao', $sala->restricao->tipo_restricao) === 'PERIODO_LETIVO') selected @endif>Datas limites definidas pelo Período Letivo</option>
                            </select>

                    </div>
                </div>
            </div>


            <div class="row" id="box_restricao_tipo_fixa"  @if (old('tipo_restricao', $sala->restricao->tipo_restricao) !== 'FIXA') style="display: none" @endif>
                <div class="col-sm form-group">
                    <b>Data limite fixa</b>
                    <br>Data limite que a sala aceita reservas. Ao habilitar essa opção, o sistema não permite a reserva da sala que além da data escolhida, mesmo que a sala esteja livre.

                        <div class="form-group">
                            <label for="data">Escolha uma data:</label>
                            <input type="date" name="data_limite" id="txt_data_limite" min="{{ \Carbon\Carbon::now()->addDay()->format('Y-m-d') }}"
                                value="{{ old('data', isset($sala->restricao->data_limite) ? $sala->restricao->data_limite : null) }}"
                                min="{{ \Carbon\Carbon::now()->addDay()->format('Y-m-d') }}">
                        </div>
                </div>
            </div>


            <div class="row" id="box_restricao_tipo_auto" @if (old('tipo_restricao', $sala->restricao->tipo_restricao) !== 'AUTO') style="display: none" @endif>
                <div class="col-sm form-group">
                    <b>Data limite dinâmica</b>
                    <br>Número de dias a contar do momento da solicitação para que o sistema determine dinamicamente a data limite. Ao habilitar essa opção,
                    o sistema não permite a reserva da sala além do número de dias indicado, mesmo que a sala esteja livre.

                        <div class="form-group">
                            <label for="dias_limite">Número de dias de limite:</label>
                            <input type="number" name="dias_limite" id="txt_dias_limite" value="{{ $sala->restricao->dias_limite }}" min="1" >
                        </div>
                </div>
            </div>


            <div class="row" id="box_restricao_tipo_periodo_letivo"  @if (old('tipo_restricao', $sala->restricao->tipo_restricao) !== 'PERIODO_LETIVO') style="display: none" @endif>
                <div class="col-sm form-group">
                    <b>Período letivo</b>
                    <br>Número de dias a contar do momento da solicitação para que o sistema determine dinamicamente a data limite. Ao habilitar essa opção,
                    o sistema não permite a reserva da sala além do número de dias indicado, mesmo que a sala esteja livre.

                        <div class="form-group">
                            <label for="select_periodo_letivo">Período letivo:</label>

                            <select name="periodo_letivo" id="select_periodo_letivo">
                                <option value="">Escolha um período</option>
                                @foreach ($periodos as $periodo)

                                        <option value="{{ $periodo->id }}" {{ old('periodo_letivo', $sala->restricao->periodo_letivo_id) == $periodo->id ? 'selected' : '' }}>
                                        {{ $periodo->codigo }}
                                        (Janela de
                                        {{ \Carbon\Carbon::parse($periodo->data_inicio_reservas)->format('d/m/Y')  }}
                                        a
                                        {{ \Carbon\Carbon::parse($periodo->data_fim_reservas)->format('d/m/Y')  }}
                                        )

                                    </option>
                                @endforeach
                            </select>
                        </div>
                </div>
            </div>

            <!-- fim das restrições -->

        @elseif($title == 'Nova')
            <input type="hidden" name="aprovacao" value="0">
            <input type="hidden" name="tipo_restricao" value="NENHUMA" >
        @endif
        <button type="submit" class="btn btn-success"> Enviar </button>
        <br>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function() {
        aprovacaoChanged();
    });

    function aprovacaoChanged() {
        if ($("#aprovacao-sim").is(":checked")) {
            $('#exige_justificativa_recusa_sim_nao').show();
            $('#prazo_aprovacao_sim_nao').show();
        } else {
            $('#exige_justificativa_recusa_sim_nao').hide();
            $("#exige-justificativa-recusa-nao").prop("checked", true);
            $('#prazo_aprovacao_sim_nao').hide();
            $("#aprovacao-prazo-nao").prop("checked", true);
        }
        aprovacaoPrazoChanged();
    }

    function aprovacaoPrazoChanged() {
        if ($("#aprovacao-prazo-sim").is(":checked"))
            $('#prazo_aprovacao_prazo').show();
        else {
            $('#prazo_aprovacao_prazo').hide();
            $('#number_prazo_aprovacao').val('');
        }
    }
</script>
