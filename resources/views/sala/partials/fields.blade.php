<div class="card">
    <div class="card-header">
        <h5>{{ $sala->nome }}</h5>
    </div>
        <div class="card-body">
            <ul class="list-group list-group-flush">
                <li class="list-group-item" ><h6>Nome</h6><a href="/salas/{{$sala->id}}">{{  $sala->nome ?? ''  }}</a></li>
                <li class="list-group-item" ><h6>Categoria</h6>
                @foreach($sala::categorias() as $categoria)
                @if($sala->categoria_id == $categoria->id)
                    {{  $categoria->nome  }}<br>
                @endif
                @endforeach</li>
                <li class="list-group-item" ><h6>Capacidade</h6>{{  $sala->capacidade ?? ''  }}</li>
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
</div>
<br>