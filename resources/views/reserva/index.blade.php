@extends('main')
@section('title') Sistema de Reserva de Salas @endsection
@section('content')
<div class="card">
    <div class="card-body">
        <form method="get" action="/reservas">
            <div class="row">
                <div class=" col-sm input-group">
                    <input type="text" class="form-control" id="input_busca_nome" name="busca_nome" type="text" placeholder="Reserva" value="{{ request()->busca_nome }}">                    <span class="input-group-btn">
                        <button type="submit" class="btn btn-success"> Buscar </button>
                    </span>
                </div>
            </div>
        </form>
        <br>
        {{ $reservas->appends(request()->query())->links() }}
        @include('reserva.partials.table')
        
    </div>
</div>
@endsection
