<h2>Nova exclusão de reserva(s) de sala no site <a href="{{route('home')}}" >{{route('home')}}</a>.</h2>

<h3><b>Título:</b> {{$reserva->nome}} </h3>
<p><b>Horário:</b> {{$reserva->horario_inicio}} </p>
<p><b>Sala:</b> {{$reserva->sala->nome}} </p>
<p><b>Finalidade:</b> {{$reserva->finalidade->legenda}} </p>
<p><b>Excluída por:</b> {{ $changed_by['name'] ?? 'Usuário autenticado' }}</p>
<p><b>Quando:</b> {{ $changed_at->format('d/m/Y H:i') }}</p>

<h3>Dados da reserva antes da exclusão</h3>
<table>
    <tr><th>Campo</th><th>Antes</th><th>Depois</th></tr>
    @foreach($changes as $change)
        <tr>
            <td>{{ $change['label'] }}</td>
            <td>{{ $change['old'] }}</td>
            <td>{{ $change['new'] }}</td>
        </tr>
    @endforeach
</table>

@if($purge)
    <p><b>Datas:</b>
    @foreach($reserva->irmaos() as $reserva)
        {{$reserva->data}},
    @endforeach
    <p>
@else
    <p><b>Data:</b> {{$reserva->data}} </p>
@endif

@if (!empty($justificativa))
    <br>
    <div style="border: 1px solid #ccc; padding: 10px; background-color: #f9f9f9;">
        <strong>Motivo da Recusa:</strong><br>
        {{ $justificativa }}
    </div>
@endif

<br>

<p>Mensagem automática do sistema de reserva de salas: {{route('home')}}</p>
