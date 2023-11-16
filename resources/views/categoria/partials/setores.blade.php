<div class="card">
    <div class="card-header">
      <b>Setores cadastrados em {{ $categoria->nome }}</b>
    </div>
    <div class="card-body">
        <form method="POST">
            @csrf
            <select name="" id="" class="select2 form-control" multiple="multiple">
                @foreach ($setores as $setor)
                    <option value="{{$setor['codset']}}">{{$setor['nomabvset']}} - {{$setor['nomset']}}</option>
                @endforeach
            </select>
            <button class="btn btn-success mt-3" type="submit">Salvar</button>
        </form>
    </div>
</div>
