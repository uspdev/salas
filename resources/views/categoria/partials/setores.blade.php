<div class="card">
    <div class="card-header">
      <b>Setores cadastrados em {{ $categoria->nome }}</b>
    </div>
    <div class="card-body">
        <form method="POST" action="{{route('categoria.add-setor', $categoria)}}">
            @csrf
            <select name="setores[]" class="select2 form-control" multiple="multiple">
                @foreach ($setores as $setor)
                    <option value="{{$setor['codset']}}" {{$categoria->setores->contains('codset', $setor['codset']) ? 'selected': ''}}>{{$setor['nomabvset']}} - {{$setor['nomset']}}</option>
                @endforeach
            </select>
            <button class="btn btn-success mt-3" type="submit">Salvar</button>
        </form>
    </div>
</div>
