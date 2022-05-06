<table class="table table-borderless">
    <div class="table-responsive">
        <tr>
            <th>Sala</th>
            <th>Capacidade</th>
        </tr>
        @forelse($salas as $sala)
        <tr>
            <td><a href="/salas/{{ $sala->id }}">{{ $sala->nome }}</a></td>
            <td>{{ $sala->capacidade }}</td>
        </tr>
        @empty
            <p>Não há salas cadastradas ainda.</p>
        @endforelse
    </div>
</table>