{{ dd($categoria )}}
<div class="card">
    <div class="card-header">
    <h5>{{ $categoria->nome }}</h5>
    </div>
    <div class="card-body">
        <ul class="list-group list-group-flush">
            <li class="list-group-item" ><h6>Nome</h6><a href="/categorias/{{$categoria->id}}">{{  $categoria->nome ?? ''  }}</a></li>  
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
</div>
<br>