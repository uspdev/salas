<div class="card">
    <div class="card-header">
      <b>Vínculos cadastrados em {{ $categoria->nome }}</b>
    </div>
    <div class="card-body">
        <form method="POST" action="{{route('alterar-vinculos', ['categoria' => $categoria])}}">
            @csrf
            <p>
                Referente apenas aos vínculos de <b>Docentes, Servidores e Estagiários</b>:
            </p>
            <div class="form-check mb-3">
                <input class="form-check-input radio-box" type="radio" name="vinculo" value="2" id="usp" {{$categoria->vinculos == 2 ? "checked" : ""}}>
                <label class="form-check-label" for="usp">
                  <b>USP</b> (Todas as pessoas que entrem com senha única)
                </label>
            </div>
            
            <div class="form-check mb-3">
                <input class="form-check-input radio-box" type="radio" name="vinculo" value="1" id="eca" {{$categoria->vinculos == 1 ? "checked" : ""}}>
                <label class="form-check-label" for="eca">
                  <b>{{$sigla_unidade}}</b> (Todas as pessoas da unidade)
                </label>
            </div>
            
            <div class="form-check mb-3">
                <input class="form-check-input radio-box" type="radio" name="vinculo" value="0" id="nenhum" {{$categoria->vinculos == 0 ? "checked" : ""}}>
                <label class="form-check-label" for="nenhum">
                  <b>Nenhum</b> (Apenas as pessoas cadastradas manualmente)
                </label>
            </div>
            @can('admin')
            <button class="btn btn-success" type="submit">Salvar</button>
            @endcan
        </form>
    </div>
</div>
