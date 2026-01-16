<h2>Nova exclusão de reserva(s) de sala no site <a href="{{route('home')}}" >{{route('home')}}</a>.</h2>

<h3><b>Título:</b> {{$reserva->nome}} </h3>
<p><b>Horário:</b> {{$reserva->horario_inicio}} </p>
<p><b>Sala:</b> {{$reserva->sala->nome}} </p>
<p><b>Finalidade:</b> {{$reserva->finalidade->legenda}} </p>

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
