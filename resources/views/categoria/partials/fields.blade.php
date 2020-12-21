<div class="card-body">
    <ul class="list-group list-group-flush">
        <li class="list-group-item" ><h5>Nome</h5><a href="/categorias/{{$categoria->id}}">{{  $categoria->nome ?? ''  }}</a></li>  
    </ul>
    </br>
    <div class="col-sm form-group">
        <form action="/categorias/{{  $categoria->id  }}" method="POST">
            <a class="btn btn-outline-success" href="/categorias/{{  $categoria->id  }}/edit" role="button">Editar</a>
            @csrf
            @method('delete')
            <button class="btn btn-outline-danger" type="submit" onclick="return confirm('Tem certeza?');">Apagar</button> 
        </form>
    </div>
</div>