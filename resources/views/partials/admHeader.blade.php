@can('admin')
    <div class="card">
        <div class="card-header">
            <b>Administração</b>
        </div>
        <div class="card-body">
            <a href="/{{ $c ?? '' }}/create" class="btn btn-success">Cadastrar {{ $c ?? '' }}</a>
        </div>
    </div>
    <br>
@endcan