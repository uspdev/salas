<style>
    .rectangle {
    height: 50px;
    width: 50px;
    background-color: #ffffff;
    border: 2px solid #ffffff;
    border-radius: 5px;

    }
</style>

<div class="card-body">
    <ul class="list-group list-group-flush">
        <li class="list-group-item" ><h5>Nome</h5><a href="/reservas/{{$reserva->id}}">{{  $reserva->nome ?? ''  }}</a></li>
        <li class="list-group-item" ><h5>Data de início</h5>{{  $reserva->data_inicio ?? ''  }}</li>
        <li class="list-group-item" ><h5>Data de fim</h5>{{  $reserva->data_fim ?? ''  }}</li>
        <li class="list-group-item" ><h5>Evento de dia inteiro?</h5>
        @if ($reserva->full_day_event == 1)
            Sim
        @else
            Não
        @endif</li>
        <li class="list-group-item" ><h5>Horário de início</h5>{{  $reserva->horario_inicio ?? ''  }}</li>
        <li class="list-group-item" ><h5>Horário de fim</h5>{{  $reserva->horario_fim ?? ''  }}</li>
        <li class="list-group-item" ><h5>Sala</h5>{{  $reserva->sala_id ?? ''  }}</li>
        <li class="list-group-item" ><h5>Cor</h5><div class="rectangle" style="background-color: {{  $reserva->cor ?? ''  }};"></div></li>
        <li class="list-group-item" ><h5>Descrição</h5>{{ $reserva->descricao ?? '' }}</li>
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