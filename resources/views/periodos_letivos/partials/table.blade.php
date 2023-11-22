<table class="table table-borderless">
    <div class="table-responsive">
        <tr>
            <th>Período Letivo</th>
            <th>Data de início</th>
            <th>Data de término</th>
            <th>Data de início das reservas</th>
            <th>Data de término das reservas</th>
            <th></th>
        </tr>
        @forelse($periodos as $periodo)
        <tr>
            <td>{{ $periodo->codigo }}</td>
            <td>{{ \Carbon\Carbon::parse($periodo->data_inicio)->format('d/m/Y') }}</td>
            <td>{{ \Carbon\Carbon::parse($periodo->data_fim)->format('d/m/Y') }}</td>
            <td>{{ \Carbon\Carbon::parse($periodo->data_inicio_reservas)->format('d/m/Y') }}</td>
            <td>{{ \Carbon\Carbon::parse($periodo->data_fim_reservas)->format('d/m/Y') }}</td>
            <td>
                <form method="POST" action="/periodos_letivos/{{ $periodo->id }}">
                    @csrf
                    @method('delete')
                    <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Tem certeza?');">
                        <i class="fa fa-trash" ></i>
                    </button>
                </form>
            </td>
        </tr>
        @empty
            <p>Não há salas cadastradas ainda.</p>
        @endforelse
    </div>
</table>