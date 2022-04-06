<table class="table table-borderless">
    <div class="table-responsive">
        <tr>
            <th>Sala</th>
            <th>Categoria</th>
            <th>Capacidade</th>
        </tr>
        <tr>
            <td><a href="/salas/{{ $sala->id }}">{{ $sala->nome }}</a></td>
            <td> {{ $sala->categoria->nome }} </td>
            <td>{{  $sala->capacidade ?? ''  }}</td>
        </tr>
    </div>
</table>
</br>
@can('admin')
    <form action="/salas/{{  $sala->id  }}" method="POST">
        <a class="btn btn-success" href="/salas/{{  $sala->id  }}/edit" role="button">Editar</a>
        @csrf
        @method('delete')
        <button class="btn btn-danger" type="submit" onclick="return confirm('Tem certeza?');">Apagar</button> 
    </form>
@endcan
