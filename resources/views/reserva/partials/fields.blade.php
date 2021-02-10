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
        <form action="/reservas/{{  $reserva->id  }}" method="POST">
            <a class="btn btn-success" href="/reservas/{{  $reserva->id  }}/edit" role="button">Editar</a>
            @csrf
            @method('delete')
            <button class="btn btn-danger" type="submit" name="tipo" value="one" onclick="return confirm('Tem certeza?');">Apagar</button>
            @if($reserva->parent_id != null)
                <button class="btn btn-danger" type="submit" name="tipo" value="all" onclick="return confirm('Todas instâncias serão deletadas');">Apagar todas instâncias</button> 
            @endif
        </form>
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
                        @foreach($reserva::salas() as $sala)
                            @if($reserva->sala_id == $sala->id)
                                {{  $sala->nome  }}
                            @endif
                        @endforeach
                    </td>
                    <td>{{ $reserva->descricao ?: 'Sem descrição' }}</td>
                    <td>
                    <div class="rectangle" style="background-color: {{  $reserva->cor ?? ''  }};"></div>
                    </td>
                </tr>
            </div>
        </table>
        <div class="card">
            <div class="card-header" type="button" data-toggle="collapse" data-target="#collapse{{ $reserva->id }}" aria-expanded="false" aria-controls="collapse{{ $reserva->id }}">
            <span><b>Reservas do mesmo grupo</b></span>
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-plus-square" viewBox="0 0 16 16">
                <path d="M14 1a1 1 0 0 1 1 1v12a1 1 0 0 1-1 1H2a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1h12zM2 0a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V2a2 2 0 0 0-2-2H2z"/>
                <path d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4z"/>
            </svg>
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
        <a class="btn btn-primary" href="/reservas" role="button">Voltar</a>
    </div>
</div>