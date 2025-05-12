<h2><a href="{{route('reservas.show', ['reserva' => $reserva->id])}}">Reserva</a> automaticamente aprovada no site <a href="{{route('home')}}" >{{route('home')}}</a></h2>

<h3><b>Título:</b> {{$reserva->nome}} </h3>
<p><b>Horário:</b> {{$reserva->horario_inicio}} </p>
<p><b>Sala:</b> {{$reserva->sala->nome}} </p>
<p><b>Finalidade:</b> {{$reserva->finalidade->legenda}} </p>

@if($reserva->irmaos())
    <p><b>Datas:</b>
    @foreach($reserva->irmaos() as $reserva)
        {{$reserva->data}},
    @endforeach
    <p>
@else
    <p><b>Data:</b> {{$reserva->data}} </p>
@endif

<p><b>Status da reserva:</b> {{ucfirst($reserva->status)}} </p>

<p>Como você não aprovou nem rejeitou a reserva dentro do prazo, o sistema automaticamente a aprovou.</p>

<br>
<p>Mensagem automática do sistema de reserva de salas: {{route('home')}}</p>
