@extends('main')

@section('content')
    <a href="{{route('categorias.create')}}" class="btn btn-success mb-3">Cadastrar Categoria</a>
    <div class="card">
        <div class="card-header"><b>Categorias</b></div>
        <div class="card-body">
            <table class="table table-striped">
                <div class="table-responsive">
                    <tr>
                        <th>Nome da Categoria</th>
                        <th></th>
                    </tr>
                    @forelse($categorias as $categoria)
                        <tr>
                            <td>{{ $categoria->nome }}</td>
                            <td class="d-flex justify-content-end">
                                @can('admin')
                                <form method="POST" action="{{route('categorias.destroy', $categoria->id)}}">
                                    <a class="btn btn-success" href="{{route('categorias.show', $categoria->id)}}" role="button"
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
                        <p>Não há categorias cadastradas ainda.</p>
                    @endforelse
                </div>
            </table>
        </div>
    </div>
@endsection