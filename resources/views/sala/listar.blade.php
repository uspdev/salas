@extends('main')

@section('content')
    <a href="{{route('salas.create')}}" class="btn btn-success mb-3">Cadastrar Sala</a>
    <div class="card">
        <div class="card-header"><b>Salas</b></div>
        <div class="card-body">
            <table class="table table-striped">
                <div class="table-responsive">
                    <tr>
                        <th>Nome da Sala</th>
                        <th></th>
                    </tr>
                    @forelse($salas as $sala)
                        <tr>
                            <td>{{ $sala->nome }}</td>
                            <td class="d-flex justify-content-end">
                                @can('admin')
                                <form method="POST" action="{{route('salas.destroy', $sala->id)}}">
                                    <a class="btn btn-success" href="{{route('salas.edit', $sala->id)}}" role="button"
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
                        <p>Não há salas cadastradas ainda.</p>
                    @endforelse
                </div>
            </table>
        </div>
    </div>
@endsection