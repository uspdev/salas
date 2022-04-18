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
                    <a class="btn btn-success" href="/reservas/{{  $reserva->id  }}/edit" role="button" data-bs-toggle="tooltip" title="Editar">
                        <i class="fa fa-pen"></i>
                    </a>
                    @csrf
                    @method('delete')
                    <button class="btn btn-danger" type="submit" name="tipo" value="one" data-bs-toggle="tooltip" title="Excluir" onclick="return confirm('Tem certeza?');">
                        <i class="fa fa-trash" ></i>
                    </button>
                </form>
            </div>
            @if($reserva->parent_id != null)
                <div style="margin-left: 5%;">
                    <form action="/reservas/{{  $reserva->id  }}" method="POST">
                        <a class="btn btn-success" href="/reservas/{{  $reserva->id  }}/editAll" role="button" data-bs-toggle="tooltip" title="Editar todas">
                            <i class="fas fa-edit"></i>
                        </a>
                        @csrf
                        @method('delete')
                        <button class="btn btn-danger" type="submit" name="tipo" value="all" data-bs-toggle="tooltip" title="Excluir todas" onclick="return confirm('Todas instâncias serão deletadas!');">
                            <i class="fas fa-dumpster"></i>
                        </button> 
                    </form>    
                </div>
            @endif
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
                    <div class="rectangle" style="background-color: {{  $reserva->cor ?? ''  }};"></div>
                    </td>
                </tr>
            </div>
        </table>

        @if($reserva->irmaos())
            <div class="card-body">
                <b>Recorrências:</b>
                @foreach($reserva->irmaos() as $reserva)
                    <a href="/reservas/{{ $reserva->id }}">{{ $reserva->data }}</a>,
                @endforeach
            </div>
        @endif
        <br>

        <br>
    </div>
</div>