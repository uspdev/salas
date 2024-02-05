<table class="table table-striped">
    <div class="table-responsive">
        <thead>
            <tr>
                <th>Período Letivo</th>
                <th>Data de início <br>do período letivo</th>
                <th>Data de término <br>do período letivo</th>
                <th>Data de início das reservas <br>para o período letivo</th>
                <th>Data de término das reservas <br>para o período letivo</th>
                <th></th>
            </tr>
        </thead>
        @forelse($periodos as $periodo)
            <tr>
                <td>{{ $periodo->codigo }}</td>
                <td>{{ \Carbon\Carbon::parse($periodo->data_inicio)->format('d/m/Y') }}</td>
                <td>{{ \Carbon\Carbon::parse($periodo->data_fim)->format('d/m/Y') }}</td>
                <td>{{ \Carbon\Carbon::parse($periodo->data_inicio_reservas)->format('d/m/Y') }}</td>
                <td>{{ \Carbon\Carbon::parse($periodo->data_fim_reservas)->format('d/m/Y') }}</td>
                <td>
                    @can('admin')
                    <form method="POST" action="/periodos_letivos/{{ $periodo->id }}">
                        <a class="btn btn-success" href="/periodos_letivos/{{ $periodo->id }}/edit" role="button"
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
            <p>Não há períodos letivos cadastradas ainda.</p>
        @endforelse
    </div>
</table>
