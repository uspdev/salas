<style>
    .rectangle {
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
                    <th>Data</th>
                    <th>Horário</th>
                    <th>Sala</th>
                    <th>Descrição</th>
                    <th>Cor</th>
                </tr>
                <tr>
                    <td><a href="/reservas/{{ $reserva->id }}">{{ $reserva->nome }}</a></td>
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
        <div class="card">
            <div class="card-header" type="button" data-toggle="collapse" data-target="#collapse{{ $reserva->id }}" aria-expanded="false" aria-controls="collapse{{ $reserva->id }}">
                <span><b>Reservas do mesmo grupo</b></span>
                <i class="far fa-plus-square"></i>
            </div>
        </div>
        <div class="collapse" id="collapse{{ $reserva->id }}">
            <div class="card card-body">
                <ul class="list-group list-group-flush">  
                @if($reserva->irmaos())
                    @foreach($reserva->irmaos() as $reserva)
                        <li class="list-group-item"><a href="/reservas/{{ $reserva->id }}">{{ $reserva->data }}</a></li>
                    @endforeach
                @else
                    Não há reservas do mesmo grupo.
                @endif
                </ul>
            </div>
        </div><br>
        <a class="btn btn-outline-dark" href="/reservas" role="button">
            <i class="fas fa-arrow-left"></i> Voltar
        </a>
    </div>
</div>