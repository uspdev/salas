<div class="card">
    <div class="card-header">
        <b>{{ $categoria->nome }}</b>
        @can('admin')
        <div>
            <form action="/categorias/{{  $categoria->id  }}" method="POST">
                <a class="btn btn-success" href="/categorias/{{  $categoria->id  }}/edit" role="button" data-bs-toggle="tooltip" title="Editar">
                    <i class="fa fa-pen"></i>
                </a>
                @csrf
                @method('delete')
                <button class="btn btn-danger" type="submit" name="deletar_categoria" value="one" data-bs-toggle="tooltip" title="Excluir" onclick="return confirm('Tem certeza?');">
                    <i class="fa fa-trash" ></i>
                </button>
            </form>
        </div>
        @endcan
    </div>
    <div class="card-body">
    @include('categoria.partials.vinculos')
    <br>
    @include('categoria.partials.setores')
    <br>
    @include('categoria.partials.addForm')
    <br>
    @include('categoria.partials.pesIndex')
    </div>
</div>
<br>