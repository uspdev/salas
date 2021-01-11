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
    <h5>{{ $reserva->nome }}</h5>
    </div>
    <div class="card-body">
        <ul class="list-group list-group-flush">
            <li class="list-group-item" ><h6>Nome</h6><a href="/reservas/{{$reserva->id}}">{{  $reserva->nome ?? ''  }}</a></li>
            <li class="list-group-item" ><h6>Data de início</h6>{{  $reserva->data_inicio ?? ''  }}</li>
            <li class="list-group-item" ><h6>Data de fim</h6>{{  $reserva->data_fim ?? ''  }}</li>
            <li class="list-group-item" ><h6>Evento de dia inteiro?</h6>
            @if ($reserva->full_day_event == 1)
                Sim
            @else
                Não
            @endif</li>
            <li class="list-group-item" ><h6>Horário de início</h6>{{  $reserva->horario_inicio ?? ''  }}</li>
            <li class="list-group-item" ><h6>Horário de fim</h6>{{  $reserva->horario_fim ?? ''  }}</li>
            <li class="list-group-item" ><h6>Sala</h6>
            @foreach($reserva::salas() as $sala)
                @if($reserva->sala_id == $sala->id)
                    {{  $sala->nome  }}<br>
                @endif
            @endforeach</li>
            <li class="list-group-item" ><h6>Cor</h6><div class="rectangle" style="background-color: {{  $reserva->cor ?? ''  }};"></div></li>
            <li class="list-group-item" ><h6>Descrição</h6>{{ $reserva->descricao ?? '' }}</li>
        </ul>
        </br>
        <div class="col-sm form-group">
            <form action="/reservas/{{  $reserva->id  }}" method="POST">
                <a class="btn btn-outline-success" href="/reservas/{{  $reserva->id  }}/edit" role="button">Editar</a>
                @csrf
                @method('delete')
                <button class="btn btn-outline-danger" type="submit" onclick="return confirm('Tem certeza?');">Apagar</button> 
            </form>
        </div>
    </div>
</div>
<br>