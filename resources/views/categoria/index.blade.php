<div class="card">
    <div class="card-header">
    <ul class="nav nav-tabs card-header-tabs">
        <li class="nav-item">
            <a class="nav-link active" aria-current="true" href="/reservas"><b>Categorias</b></a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="/salas"><b>Salas</b></a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="/reservas"><b>Reservas</b></a>
        </li>
        </ul>    
    </div>
    <div class="card-body">
        <form method="get" action="/reservas">
            <div class="row">
                <div class=" col-sm input-group">
                    <input type="text" class="form-control" name="search" type="text" placeholder="Busca por categoria..." aria-label="default input example" value="{{ request()->search }}">
                    <span class="input-group-btn">
                        <button type="submit" class="btn btn-success"> Buscar </button>
                    </span>
                </div>
            </div>
        </form><br>
        @foreach($categorias as $categoria)
        <div class="card">
            <div class="card-header" type="button" data-toggle="collapse" data-target="#collapse{{ $categoria->id }}" aria-expanded="false" aria-controls="collapse{{ $categoria->id }}">
            <a href="/categorias/{{ $categoria->id }}">{{ $categoria->nome }}</a>
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-plus-square" viewBox="0 0 16 16">
                <path d="M14 1a1 1 0 0 1 1 1v12a1 1 0 0 1-1 1H2a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1h12zM2 0a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V2a2 2 0 0 0-2-2H2z"/>
                <path d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4z"/>
            </svg>
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
        </div><br>
        @endforeach
    </div>
    </div>  
</div>

