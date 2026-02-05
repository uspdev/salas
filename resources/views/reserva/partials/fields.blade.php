<style>
    .rectangle {
        height: 50px;
        width: 50px;
        background-color: #ffffff;
        border: 2px solid #ffffff;
        border-radius: 5px;
    }

    #reserva-header {
        display: flex;
        justify-content: space-between;
    }

    .adm-icons {
        width: 30%;
        display: flex;
        justify-content: end;
    }
</style>

@if ($reserva->status == 'pendente')
   <div style="background-color: {{config('salas.cores.pendente')}}" class="p-2 mb-2 rounded">
     Pendente
   </div>
@endif

<div class="card">
    <div class="card-header" id="reserva-header">
        <div>
            <b>{{ $reserva->nome }}</b>
        </div>
        @can('owner', $reserva)
        <div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Excluir Reserva</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                </div>

                <!-- Modifica a pergunta de exclusão caso a reserva tenha repetições -->
                @if (is_null($reserva->parent_id))
                    <div class="modal-body">
                        Deseja realmente excluir esta reserva?
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                        <button type="button" class="btn btn-danger" id="btn-excluir">Excluir</button>
                    </div>
                @else
                    <div class="modal-body">
                        Deseja excluir somente esta instância ou todas as repetições da reserva?
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn-purge btn btn-secondary">Somente esta intância</button>
                        <button type="button" class="btn-purge btn btn-danger" data-purge="1">Todas as repetições</button>
                    </div>
                @endif

            </div>
            </div>
        </div>
        <div class="adm-icons">
            <div>
                <form action="/reservas/{{  $reserva->id  }}" method="POST" id="form-excluir">
                    @csrf
                    @method('delete')
                    @can('reserva.editar', $reserva)
                        <a class="btn btn-success" href="/reservas/{{  $reserva->id  }}/edit" title="Editar">
                            <i class="fa fa-pen"></i>
                        </a>
                    @endcan

                    @if(!is_null($reserva->parent_id)) <input type="hidden" name="purge" id="purge"> @endif

                    <button data-toggle="modal" data-target="#deleteModal" class="btn btn-danger" type="button" title="Excluir" >
                        <i class="fa fa-trash" ></i>
                    </button>
                </form>
            </div>
        </div>
        @endcan
    </div>
    <div class="card-body">
        <div class="col-sm form-group">
        </div>
        <table class="table table-borderless">
            <div class="table-responsive">
                <tr>
                    <th>Cadastrada por</th>
                    <th>Responsáveis</th>
                    <th>Data</th>
                    <th>Horário</th>
                    <th>Sala</th>
                    <th>Descrição</th>
                    <th>Finalidade</th>
                </tr>
                <tr>
                    <td>{{ $reserva->user->name }} - {{ $reserva->user->codpes }}</td>
                    <td>{{$reserva->responsaveis->count() > 0 ? $reserva->responsaveis->pluck('nome')->implode(', ') : 'Sem responsáveis'}}</td>
                    <td>{{ $reserva->data }}</td>
                    <td>{{ $reserva->horario_inicio }} a {{ $reserva->horario_fim }}</td>
                    <td>
                        <a href="/salas/{{ $reserva->sala->id }}">{{  $reserva->sala->nome  }}</a>
                    </td>
                    <td>{{ $reserva->descricao ?: 'Sem descrição' }}</td>
                    <td>
                        @if(isset($reserva->finalidade))
                            <div style="background-color: {{ $reserva->finalidade->cor }}" class="p-2 mt-n2 rounded">{{$reserva->finalidade->legenda}}</div>
                        @else
                            <div style="background-color: #BDBDBD" class="p-2 mt-n2 rounded">Sem finalidade</div>
                        @endif
                    </td>
                </tr>
            </div>
        </table>

        @if($reserva->irmaos())
            <div class="card-body">
                <b>Recorrências:</b>
                @php
                    $reservas_array = $reserva->irmaos()->toArray();
                @endphp
                ({{ \Carbon\Carbon::createFromFormat('d/m/Y', $reservas_array[0]['data'])->translatedFormat('l') }})
                @foreach($reservas_array as $key => $reservaIterator)
                    <a href="/reservas/{{ $reservaIterator['id'] }}">{{ $reservaIterator['data'] }}</a>@if( $key !== count($reservas_array) -1 ),@endif
                @endforeach
            </div>
        @endif
        <br>

        <br>
    </div>
</div>

@if ($reserva->status == 'pendente')
    @can('responsavel', $reserva->sala)
        <form action="{{route('reservas.destroy', $reserva)}}" method="POST" id="form-reserva-recusar" onsubmit="return confirm('Recusar reserva?')">
            @csrf
            @method('DELETE')
        </form>
        <div class="mt-4">
            <a class="btn btn-success" href="{{route('reservas.aprovar', $reserva)}}"><i class="fa fa-check"></i> Aprovar</a>
            <button class="btn btn-danger" form="form-reserva-recusar"><i class="fa fa-ban"></i> Recusar</button>
        </div>
    @endcan
@endif
