@extends('main')

@section('content')
    <form method="POST" action="/reservas/{{ $reserva->id }}">
        @csrf
        @method('patch')
        @include('reserva.partials.form', ['title' => "Editar"])
    </form>
@endsection

@section('javascripts_bottom')
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <script type="text/javascript">

        $(document).ready(function() {
            $('.salas_select').select2();
        });

    </script>
@stop
