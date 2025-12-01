@extends('main')
@section('content')

<div class="card">
  <div class="card-header"><b>Gerar Excel de relatório</b></div>
    <div class="card-body">
      <form method="get" action="/query" class="form-inline">
        <div class="form-group">
          <input type="text" class="datepicker form-control" name="inicio" value="{{ old('inicio',request()->inicio) }}" placeholder="Data Inicial">
        </div>
        <div class="form-group ml-3">
            <input type="text" class="datepicker form-control" name="fim" value="{{ old('fim',request()->fim) }}" placeholder="Data Final">
        </div>
        <div class="form-group ml-3">
            <select name="categoria_id" class="form-control">
              @foreach($categorias as $id => $nome)
                <option value="{{ $id }}">{{ $nome }}</option>
              @endforeach
            </select>
        </div>
        <button type="submit" class="btn btn-success ml-3">
            <i class="fa fa-file-excel"></i>
            Gerar Excel
        </button>
      </form>
    </div>
  </div>
</div>
@endsection

@section('javascripts_bottom')
@endsection
