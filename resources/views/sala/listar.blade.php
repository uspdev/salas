@extends('main')

@section('content')
    @can ('admin')
        <a href="{{route('salas.create')}}" class="btn btn-success mb-3">Cadastrar Sala</a>
    @endcan

    <div class="card">
        <div class="card-header"><b>Salas</b></div>
        <div class="card-body">
            <form method="GET" action="{{ $origem ? $origem : route('salas.listar') }}" id="form-filtros">
                <b>Categorias:</b>
                <select name="categorias_filter[]" class="select2 form-control" multiple="multiple">
                    @foreach($categorias as $categoria)
                        <option value="{{$categoria->id}}" {{ in_array($categoria->id, $categorias_filter) ? 'selected' : ''}}>{{$categoria->nome}}</option>
                    @endforeach
                </select>

                <div class="row mt-2">
                    <div class="col-sm">
                        <b>Recursos:</b>
                        <select name="recursos_filter[]" id="" class="select2 form-control" multiple="multiple">
                            @foreach ($recursos as $recurso)
                                <option value="{{$recurso->id}}" {{in_array($recurso->id, $recursos_filter) ? 'selected' : ''}}>{{$recurso->nome}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-sm">
                        <b>Capacidade Mínima:</b>
                        <input type="number" min="0" class="form-control" name="capacidade_filter" value="{{old('capacidade_filter', $capacidade_filter)}}">
                    </div>
                </div>

                <div class="row mt-2">
                    <div class=" col-sm input-group">
                        <span class="input-group-btn">
                            <button type="submit" class="btn btn-success"> Buscar </button>
                            <a class="btn btn-secondary" id="btn-limpar-filtros">Limpar</a>
                        </span>
                    </div>
                </div>
            </form>

            <br>
            <table class="table table-striped">
                <div class="table-responsive">
                    <thead>
                        <tr>
                            <th>Nome da Sala</th>
                            <th>Categoria</th>
                            <th>Capacidade</th>
                            <th class="col-sm-2">Ações</th>
                        </tr>
                    </thead>
                    @forelse($salas as $sala)
                        <tr>
                            <td><a @can('admin') href="{{route('salas.edit', $sala->id)}}" @endcan>{{ $sala->nome }}</a></td>
                            <td>{{$sala->categoria->nome}}</td>
                            <td>{{$sala->capacidade}} pessoas</td>
                            <td>
                                @can('admin')
                                    <form method="POST" action="{{route('salas.destroy', $sala->id)}}" class="d-inline">
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

                                <a href="{{route('salas.show', $sala->id)}}" title="Calendário da sala" class="btn btn-info"><i class="fa fa-calendar"></i></a>

                                @if(Gate::allows('members', $sala->id) && !($sala->restricao->bloqueada ?? false))
                                    <a href="{{route('reservas.create', ['sala' => $sala->id])}}" title="Cadastrar reserva na sala" class="btn btn-primary"><i class="fa fa-calendar-plus"></i></a>
                                @endif

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

@section('javascripts_bottom')
    <script>
        $(document).ready(function() {
            $('#btn-limpar-filtros').on('click', function(){
                $('#form-filtros').find(':input').val("");
                $('.select2').val('val').trigger('change');
            });
        });
    </script>
@endsection
