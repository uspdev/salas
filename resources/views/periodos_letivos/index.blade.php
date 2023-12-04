@extends('main')
@section('content')
<a class="btn btn-success mb-3" href="/periodos_letivos/create" role="button"
    data-bs-toggle="tooltip" title="Adicionar" >
    Cadastrar Período Letivo
</a>

<div class="card">
    <div class="card-header">
        <b>Períodos Letivos</b>
    </div>
    
    <div class="card-body">
        @include('periodos_letivos.partials.table')
    </div>
</div>
<br>
@endsection