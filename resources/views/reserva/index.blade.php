@extends('main')
@section('title') Sistema de Reserva de Salas @endsection
@section('content')
<div class="card">
    <div class="card-body">
        <form method="get" action="/reservas">
            <div class="row">
                <div class=" col-sm input-group">
                    <div class="d-grid gap-2 d-md-block">
                        <span><b>Busca por</b></span>
                        <select class="form-select" aria-label="Default select example">
                            <option onclick="disable('input_busca_data', 'input_busca_nome')" selected>Reserva</option>
                            <option onclick="disable('input_busca_nome', 'input_busca_data')">Data</option>
                        </select>
                    </div>
                </div>
            </div><br>
            <div class="row">
                <div class=" col-sm input-group">
                    <input type="text" class="form-control" id="input_busca_nome" name="busca_nome" type="text" placeholder="Reserva" value="{{ request()->busca_nome }}">
                    <input type="text" class="datepicker" id="input_busca_data" name="busca_data" type="text" placeholder="Data" value="{{ request()->busca_data }}" disabled>
                    <span class="input-group-btn">
                        <button type="submit" class="btn btn-success"> Buscar </button>
                    </span>
                </div>
            </div>
        </form><br>
        @include('reserva.partials.table')
        {{ $reservas->appends(request()->query())->links() }}
    </div>
</div>
@endsection
