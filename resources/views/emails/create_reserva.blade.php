<h2>Nova(s) <a href="{{route('reservas.show', ['reserva' => $reserva->id])}}">reserva(s)</a> adicionada(s) no site <a href="{{route('home')}}" >{{route('home')}}</a></h2>

<h3><b>Título:</b> {{$reserva->nome}} </h3>
<p><b>Horário:</b> {{$reserva->horario_inicio}} </p>
<p><b>Sala:</b> {{$reserva->sala->nome}} </p>

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
