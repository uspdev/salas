<table class="table table-striped">
    <div class="table-responsive">
        <tr>
            <th>Reserva</th>
            <th>Cadastrada por</th>
            <th>Data</th>
            <th>Horário</th>
            <th>Sala</th>
        </tr>
        @forelse($reservas as $reserva)
        <tr>
            <td> 
                <a href="/reservas/{{ $reserva->id }}">{{ $reserva->nome }}</a>
                @if( $reserva->parent_id != NULL  )
                    (reserva com recorrências)
                @endif
            </td>
            <td>{{ $reserva->user->name }} - {{ $reserva->user->codpes }}</td>
            <td>{{ $reserva->data }}</td>
            <td>{{ $reserva->horario_inicio }} a {{ $reserva->horario_fim }}</td>
            <td>{{ $reserva->sala->nome }}</td>
        </tr>
        @empty
            <p>Não há reservas feitas ainda.</p>
        @endforelse
    </div>
</table>