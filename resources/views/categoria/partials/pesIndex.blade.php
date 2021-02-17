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
                    <button class="btn btn-danger btn-sm" type="button">Excluir</button>
                </li>
            @empty
                Não há pessoas cadastradas nesta categoria ainda.
            @endforelse
        </ul>
    </div>
</div>