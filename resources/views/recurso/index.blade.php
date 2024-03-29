@extends('main')
@section('content')
<a href="{{route('recursos.create')}}" class="btn btn-success mb-3">Cadastrar Recurso</a>
<div class="card">
    <div class="card-header">
        <b>Recursos</b>
    </div>
    <div class="card-body">
        <table class="table table-striped">
            <div class="table-responsive">
                <thead>
                    <tr>
                        <th>Nome do Recurso</th>
                        <th></th>
                    </tr>
                </thead>
                @forelse($recursos as $recurso)
                    <tr>
                        <td><a href="{{route('recursos.edit', $recurso->id)}}">{{$recurso->nome}}</a></td>
                        <td class="d-flex justify-content-end">
                            @can('admin')
                            <form method="POST" action="{{route('recursos.destroy', $recurso->id)}}">
                                <a class="btn btn-success" href="{{route('recursos.edit', $recurso->id)}}" role="button"
                                    data-bs-toggle="tooltip" title="Editar">
                                    <i class="fa fa-pen"></i>
                                </a>
                                @csrf
                                @method('delete')
                                <button type="submit" class="btn btn-danger" onclick="return confirm('Tem certeza?');">
                                    <i class="fa fa-trash"></i>
                                </button>
                            </form>
                            @endcan
                        </td>
                    </tr>
                @empty
                    <p>Não há recursos cadastrados ainda.</p>
                @endforelse
            </div>
        </table>
    </div>
</div>
<br>
@endsection