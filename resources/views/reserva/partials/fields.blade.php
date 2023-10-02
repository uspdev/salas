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

<div class="card">
    <div class="card-header" id="reserva-header">
        <div>
            <b>{{ $reserva->nome }}</b>
        </div>
        @can('owner', $reserva)
        <div class="adm-icons">
            <div>
                <form action="/reservas/{{  $reserva->id  }}" method="POST">
                    @csrf
                    @method('delete')
                    <a class="btn btn-success" href="/reservas/{{  $reserva->id  }}/edit" title="Editar">
                        <i class="fa fa-pen"></i>
                    </a>

                    <button class="btn btn-danger" type="submit" title="Excluir" 
                        onclick="return confirm('Tem certeza que deseja excluir a(s) reserva(s)?');" >
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
                    <th>Data</th>
                    <th>Horário</th>
                    <th>Sala</th>
                    <th>Descrição</th>
                    <th>Cor</th>
                </tr>
                <tr>
                    <td>{{ $reserva->user->name }} - {{ $reserva->user->codpes }}</td>
                    <td>{{ $reserva->data }}</td>
                    <td>{{ $reserva->horario_inicio }} a {{ $reserva->horario_fim }}</td>
                    <td>
                        <a href="/salas/{{ $reserva->sala->id }}">{{  $reserva->sala->nome  }}</a>
                    </td>
                    <td>{{ $reserva->descricao ?: 'Sem descrição' }}</td>
                    <td>
                    <div class="rectangle" style="background-color: {{  $reserva->status == 'pendente' ? config('salas.corPendente') : ($reserva->cor ?? '')}};"></div>
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
    @can('responsavel', $reserva->sala->id)
        <div class="mt-4">
            <a class="btn btn-success" href="{{route('reservas.aprovar', $reserva)}}"><i class="fa fa-check"></i> Aprovar</a>
            <a class="btn btn-danger"><i class="fa fa-ban"></i> Recusar</a>
        </div>
    @endcan
@endif