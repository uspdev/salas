<div class="card-body">
    <ul class="list-group list-group-flush">
        <li class="list-group-item" ><h5>Nome</h5><a href="/reserva/{{$reserva->id}}">{{  $reserva->nome ?? ''  }}</a></li>
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