<div class="card-body">
    <ul class="list-group list-group-flush">
        <li class="list-group-item" ><h5>Nome</h5><a href="/salas/{{$sala->id}}">{{  $sala->nome ?? ''  }}</a></li>
        <li class="list-group-item" ><h5>Categoria</h5>{{  $sala->categoria_id ?? ''  }}</li>
        <li class="list-group-item" ><h5>Capacidade</h5>{{  $sala->capacidade ?? ''  }}</li>
    </ul>
    </br>
    <div class="col-sm form-group">
        <form action="/salas/{{  $sala->id  }}" method="POST">
            <a class="btn btn-outline-success" href="/salas/{{  $sala->id  }}/edit" role="button">Editar</a>
            @csrf
            @method('delete')
            <button class="btn btn-outline-danger" type="submit" onclick="return confirm('Tem certeza?');">Apagar</button> 
        </form>
    </div>
</div>