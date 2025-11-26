@extends('main')
@section('content')

<div class="card">
    <div class="card-header"><b>Gerar Excel de relatório</b></div>
    <div class="card-body">
        <form method="get" action="/query">
            <div class="row">
                <div class="col">
                    <label for="inicio">Selecione a data inicial</label>
                    <input type="text" class="datepicker form-control" name="inicio" value="{{ old('inicio',request()->inicio) }}">
                </div>
                <div class="col">
                    <label for="fim">Selecione a data final</label>
                    <input type="text" class="datepicker form-control" name="fim" value="{{ old('fim',request()->fim) }}">
                </div>
                <div class="col">
                    <label for="categoria_id"><b>Selecione a categoria</b></label>
                    <select name="categoria_id" class="form-control">
                        @foreach($categorias as $categoria)
                        <option value="{{ $categoria->id }}">{{ $categoria->nome }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <button type="submit" class="btn btn-success">
                <i class="fa fa-file-excel"></i> 
                Gerar Excel
            </button>
        </form>
    </div>
</div>
@endsection

@section('javascripts_bottom')
@endsection