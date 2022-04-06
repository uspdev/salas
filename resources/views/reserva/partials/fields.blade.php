<style>
    .rectangle 
    {
    height: 50px;
    width: 50px;
    background-color: #ffffff;
    border: 2px solid #ffffff;
    border-radius: 5px;
    }
</style>

<div class="card">
    <div class="card-header">
        <b>{{ $reserva->nome }}</b>
    </div>
    <div class="card-body">
        <div class="col-sm form-group">
        </div>
        <table class="table table-borderless">
            <div class="table-responsive">
                <tr>
                    <th>Reserva</th>
                    <th>Cadastrada por</th>
                    <th>Data</th>
                    <th>Horário</th>
                    <th>Sala</th>
                    <th>Descrição</th>
                    <th>Cor</th>
                </tr>
                <tr>
                    <td><a href="/reservas/{{ $reserva->id }}">{{ $reserva->nome }}</a></td>
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
        @can('owner',$reserva)
            <form action="/reservas/{{  $reserva->id  }}" method="POST">
                <a class="btn btn-success" href="/reservas/{{  $reserva->id  }}/edit" role="button">Editar</a>
                @csrf
                @method('delete')
                <button class="btn btn-danger" type="submit" name="tipo" value="one" onclick="return confirm('Tem certeza?');">Apagar</button>
                @if($reserva->parent_id != null)
                    <button class="btn btn-danger" type="submit" name="tipo" value="all" onclick="return confirm('Todas instâncias serão deletadas');">Apagar todas instâncias</button> 
                @endif
            </form>
        @endcan
        <br>
    </div>
</div>