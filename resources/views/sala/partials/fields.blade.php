<table class="table table-borderless">
    <div class="table-responsive">
        <tr>
            <th>Sala</th>
            <th>Categoria</th>
            <th>Capacidade</th>
        </tr>
        <tr>
            <td><a href="/salas/{{ $sala->id }}">{{ $sala->nome }}</a></td>
            <td>
            @foreach($sala::categorias() as $categoria)
                @if($sala->categoria_id == $categoria->id)
                    {{  $categoria->nome  }}<br>
                @endif
            @endforeach
            </td>
            <td>{{  $sala->capacidade ?? ''  }}</td>
        </tr>
    </div>
</table>
</br>
<form action="/salas/{{  $sala->id  }}" method="POST">
    <a class="btn btn-success" href="/salas/{{  $sala->id  }}/edit" role="button">Editar</a>
    @csrf
    @method('delete')
    <button class="btn btn-danger" type="submit" onclick="return confirm('Tem certeza?');">Apagar</button> 
</form>

