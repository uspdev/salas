<h2>Reserva(s): <a href="{{route('reservas.show', ['reserva' => $reserva->id])}}">{{$reserva->id}}</a> modificada(s) no site <a href="{{route('home')}}" >{{route('home')}}</a></h2>

<h3><b>Título:</b> {{$reserva->nome}} </h3>
<p><b>Horário:</b> {{$reserva->horario_inicio}} </p>
<p><b>Sala:</b> {{$reserva->sala->nome}} </p>
<p><b>Finalidade:</b> {{$reserva->finalidade->legenda}} </p>

<p><b>Alterada por:</b> {{ $changed_by['name'] ?? 'Usuário autenticado' }}</p>
<p><b>Quando:</b> {{ $changed_at->format('d/m/Y H:i') }}</p>

<h3>Alterações</h3>
<table>
    <tr><th>Campo</th><th>Antes</th><th>Depois</th></tr>
    @forelse($changes as $change)
        <tr>
            <td>{{ $change['label'] }}</td>
            <td>{{ $change['old'] }}</td>
            <td>{{ $change['new'] }}</td>
        </tr>
    @empty
        <tr><td colspan="3">Nenhum campo foi alterado.</td></tr>
    @endforelse
</table>

@if($reserva->irmaos())
    <p><b>Datas:</b>
    @foreach($reserva->irmaos() as $reserva)
        {{$reserva->data}},
    @endforeach
    <p>
@else
    <p><b>Data:</b> {{$reserva->data}} </p>
@endif

<br>

<p>Mensagem automática do sistema de reserva de salas: {{route('home')}}</p>
