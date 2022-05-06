<div class="card">
    <div class="card-header" type="button" data-toggle="collapse" data-target="#collapse{{ $categoria->id }}" aria-expanded="false" aria-controls="collapse{{ $categoria->id }}">
    <span><b>Pessoas cadastradas em {{ $categoria->nome }}</b></span>
        <i class="far fa-plus-square"></i>
    </div>
</div>
<div class="collapse" id="collapse{{ $categoria->id }}">
    <div class="card card-body">
        <ul class="list-group">
            @forelse($categoria->users as $user)
                <li class="list-group-item" id="pesIndexList">
                    {{ $user->codpes }} - {{ $user->name }}
                    <form method="POST" action="/categorias/removeuser/{{ $categoria->id }}/{{ $user->id }}">
                        @csrf
                        @method('delete')
                        <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Tem certeza?');">
                            <i class="fa fa-trash" ></i>
                        </button>
                    </form>
                </li>
            @empty
                Não há pessoas cadastradas nesta categoria ainda.
            @endforelse
        </ul>
    </div>
</div>