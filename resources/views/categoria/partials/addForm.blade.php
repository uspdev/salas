<div class="card">
    <div class="card-header">
      <b>Adicionar pessoas em {{ $categoria->nome }}</b>
    </div>
    <div class="card-body">
        <form method="POST" action="/categorias/adduser/{{ $categoria->id }}">
            @csrf
            <div class="mb-3">
                <input name="codpes" type="addpes" class="form-control" placeholder="Número USP">
            </div>
            <button class="btn btn-success" type="submit">Enviar</button>
        </form>
    </div>
</div>