@extends('main')
@section('content')
    <div class="card">
        <div class="card-header" type="button" data-toggle="collapse" data-target="#collapse{{ $sala->id }}" aria-expanded="false" aria-controls="collapse{{ $sala->id }}">
            <span><b>{{ $sala->nome }}</b></span>
            <i class="far fa-plus-square"></i>
        </div>
    </div>
    <div class="collapse" id="collapse{{ $sala->id }}">
        <div class="card card-body">
            @include('sala.partials.fields')
        </div>
    </div>
    <div class="d-flex flex-wrap w-100 justify-content-center mt-3">
        @foreach ($finalidades as $finalidade)
            <div class="flex-item rounded" style="background-color: {{$finalidade->cor}}">{{$finalidade->legenda}}</div>
        @endforeach
            <div class="flex-item rounded" style="background-color: {{config('salas.cores.pendente')}}">Pendente</div>
            <div class="flex-item rounded" style="background-color: {{config('salas.cores.semFinalidade')}}">Sem Finalidade</div>
    </div>

    <div id="calendar" class="mt-4"></div>
@endsection  

@section('styles')
@parent
<style>
    .flex-item{
        text-align: center;
        padding: 5px 20px;
        margin: 5px;
        border: 1px solid black;
    }
</style>
@endsection

@section('javascripts_bottom')
   @parent
   <script>

        document.addEventListener('DOMContentLoaded', function() {
        var calendarEl = document.getElementById('calendar');
        var calendar = new FullCalendar.Calendar(calendarEl, {
            initialView: 'timeGridWeek',
            locale: 'pt-BR',
            expandRows: 'true',
            eventDisplay: 'block',
            allDaySlot: false,
            views: {
                timeGrid: {
                    selectable: true,
                    select: function(time){
                        if (parseInt({{Gate::allows('members', $sala->id)}})) {
                            window.location.assign("{{route('reservas.create')}}"
                                + "?data=" + time.start.toLocaleDateString('pt-BR') 
                                + "&start=" + time.start.getHours() + ":" + (time.start.getMinutes() < 10 ? '0' : '') + time.start.getMinutes() 
                                + "&end=" + time.end.getHours() + ":" + (time.end.getMinutes() < 10 ? '0' : '') + time.end.getMinutes() 
                                + "&sala={{$sala->id}}");
                        }
                    }
                },

                dayGridMonth: {
                    selectable: true,
                    select: function(time){
                        if (parseInt({{Gate::allows('members', $sala->id)}})) {
                            window.location.assign("{{route('reservas.create')}}"
                            + "?data=" + time.start.toLocaleDateString('pt-BR')
                            + "&sala={{$sala->id}}");
                        }
                    }
                }
            },
            headerToolbar:{
                left: 'prev,next today',
                center: 'title',
                right: 'dayGridMonth,timeGridWeek,timeGridDay'
            },
            buttonText: {
                today:    'Hoje',
                month:    'Mês',
                week:     'Semana',
                day:      'Dia',
                list:     'Lista',
            },
            events: {{Illuminate\Support\Js::from($eventos)}}
        });


        calendar.render();
      });

   </script>
@endsection