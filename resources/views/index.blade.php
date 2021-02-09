@extends('main')

@section('content')
  <h5>Salas por categoria</h5><br>
    @foreach($categorias as $categoria)
        <div class="card">
          <div class="card-header" type="button" data-toggle="collapse" data-target="#collapse{{ $categoria->id }}" aria-expanded="false" aria-controls="collapse{{ $categoria->id }}">
            <h5>{{ $categoria->nome }}</h5>
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-plus-square" viewBox="0 0 16 16">
              <path d="M14 1a1 1 0 0 1 1 1v12a1 1 0 0 1-1 1H2a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1h12zM2 0a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V2a2 2 0 0 0-2-2H2z"/>
              <path d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4z"/>
            </svg>
        </div>
      @if($categoria->salas->isNotEmpty())
      <ul class="list-group list-group-flush">
        @foreach($categoria->salas as $sala)

            <div class="collapse" id="collapse{{ $categoria->id }}">
                <div class="card card-body">
                  <li class="list-group-item"><a href="/salas/{{ $sala->id }}">{{ $sala->nome }} </a></li>
                </div>
            </div>
        @endforeach
      </ul>
      @else
        <div class="collapse" id="collapse{{ $categoria->id }}">
          <div class="card card-body">
              Não há salas cadastradas com esta categoria ainda.
          </div>
        </div>
      @endif
  </div>
  @endforeach
</div>
@endsection

