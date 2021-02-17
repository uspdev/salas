<div class="card">
    @include('partials.header',['c' => 'active'])
    <div class="card-body">
        @include('partials.admHeader')
        <ul class="list-group">
            @foreach($categorias as $categoria)
            <li class="list-group-item">
                <div class="card">
                    <div class="card-header" type="button" data-toggle="collapse" data-target="#collapse{{ $categoria->id }}" aria-expanded="false" aria-controls="collapse{{ $categoria->id }}">
                        @can('admin')
                            <a href="/categorias/{{ $categoria->id }}">{{ $categoria->nome }}</a>
                        @else
                            {{ $categoria->nome }}
                        @endcan
                        <i class="far fa-plus-square"></i>
                    </div>
                    <ul class="list-group list-group-flush">
                        <div class="collapse" id="collapse{{ $categoria->id }}">
                            <div class="card-body">
                                @forelse($categoria->salas as $sala)
                                    <li class="list-group-item"><a href="/salas/{{ $sala->id }}">{{ $sala->nome }} </a></li>         
                                @empty
                                    Não há salas cadastradas com esta categoria ainda.
                                @endforelse
                            </div>
                        </div>
                    </ul>
                </div>
            </li>
            @endforeach
        </ul>
    </div>  
</div>

