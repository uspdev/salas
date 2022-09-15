<h2>Foi adicionada uma nova <a href="https://salas.fflch.usp.br/reservas/{{ $reserva->id }}">reserva</a> no site <a href="https://salas.fflch.usp.br/" >salas.fflch.usp.br</a></h2>
<h3><b>Título:</b> {{$reserva->nome}} </h3>
<p><b>Data:</b> {{$reserva->data}}</p>
<p><b>Horário:</b> {{$reserva->horario_inicio}} </p>
<p><b>Sala:</b> {{$reserva->sala->nome}} </p>
<br>
<p>Mensagem automática do sistema de reserva de salas: https://salas.fflch.usp.br</p>
