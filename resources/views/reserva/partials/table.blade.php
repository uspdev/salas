<style>
    .dot 
    {
        height: 12px;
        width: 12px;
        border-radius: 50%;
        display: inline-block;
    }
</style>

<div class="table-responsive">
    <table class="table table-striped">
        <thead>
            <tr>
                <th>Data</th>
                <th>Horário</th>
                <th>Sala</th>
                <th>Categoria</th>
                <th>Finalidade</th>
                <th>Título</th>
                <th>Capacidade</th>
                <th>Descrição</th>
            </tr>
        </thead>
        <tbody>
            @forelse($reservas as $reserva)
                <tr>
                    <td>{{ $reserva->data }}</td>
                    <td>{{ $reserva->horario_inicio }} - {{$reserva->horario_fim}}</td>
                    <td><a href="/salas/{{ $reserva->sala_id }}">{{ $reserva->sala->nome }}</a></td>
                    <td>{{ $reserva->sala->categoria->nome }}</td>
                    <td><div class="dot ml-4" style="background-color: {{$reserva->status == 'pendente' ? config('salas.cores.pendente'): ($reserva->finalidade->cor ?? config('salas.cores.semFinalidade'))}};"></div></td>
                    <td><a href="/reservas/{{ $reserva->id }}">{{ $reserva->nome }}</a></td>
                    <td>{{ $reserva->sala->capacidade}} pessoas</td>
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
</div>