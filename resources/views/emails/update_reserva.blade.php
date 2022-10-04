<h2>Reserva(s): <a href="https://salas.fflch.usp.br/reservas/{{ $reserva->id }}">{{$reserva->id}}</a> modificada(s) no site <a href="https://salas.fflch.usp.br/" >salas.fflch.usp.br</a></h2>

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

<p>Mensagem automática do sistema de reserva de salas: https://salas.fflch.usp.br</p>
