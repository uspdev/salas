@extends('main')
@section('content')
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