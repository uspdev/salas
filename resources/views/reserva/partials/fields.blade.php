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
        <li class="list-group-item" ><h5>Nome</h5><a href="/reserva/{{$reserva->id}}">{{  $reserva->nome ?? ''  }}</a></li>
        <li class="list-group-item" ><h5>Data de início</h5>{{  $reserva->data_inicio ?? ''  }}</li>
        <li class="list-group-item" ><h5>Data de fim</h5>{{  $reserva->data_fim ?? ''  }}</li>
        <li class="list-group-item" ><h5>Cor</h5><div class="rectangle" style="background-color: {{  $reserva->cor ?? ''  }};"></div></li>
    </ul>
    </br>
    <div class="col-sm form-group">
        <form action="/reserva/{{  $reserva->id  }}" method="POST">
            <a class="btn btn-outline-success" href="/reserva/{{  $reserva->id  }}/edit" role="button">Editar</a>
            @csrf
            @method('delete')
            <button class="btn btn-outline-danger" type="submit" onclick="return confirm('Tem certeza?');">Apagar</button> 
        </form>
    </div>
</div>