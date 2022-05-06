<style>
    .dot 
    {
        height: 12px;
        width: 12px;
        border-radius: 50%;
        display: inline-block;
    }
</style>

<table class="table">
    <tbody>
        @forelse($reservas as $reserva)
            <tr>
                <td>{{ $reserva->data }}</td>
                <td>{{ $reserva->horario_inicio }} - {{$reserva->horario_fim}}</td>
                <td>{{ $reserva->sala->nome }}</td>
                <td>{{ $reserva->sala->categoria->nome }}</td>
                <td><div class="dot" style="background-color: {{$reserva->cor}};"></div></td>
                <td><a href="/reservas/{{ $reserva->id }}">{{ $reserva->nome }}</a></td>
                <td>Capacidade: {{ $reserva->sala->capacidade  }}</td>
                <td>{{ $reserva->descricao }}</td>
            </tr>
        @empty 
            @if(isset($data))
            <tr><td>Não há reservas registradas para {{ $data }}</td></tr>
            @else
            <tr><td>Não há reservas registradas.</td></tr>
            @endif
        @endforelse
    </tbody>
</table>