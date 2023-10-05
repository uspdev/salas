@extends('main')
@section('content')

<div class="mb-3">
    <a href="{{route('finalidades.create')}}" class="btn btn-success">Adicionar Finalidade</a>
</div>

<div class="card">
    <div class="card-header">
        Lista de Finalidades
    </div>
    <ul class="list-group list-group-flush">
        @if (count($finalidades) > 0)
            @foreach ($finalidades as $finalidade)
                <li class="list-group-item">{{$finalidade->legenda}} - {{$finalidade->cor}}</li>
            @endforeach
        @else
            <li class="list-group-item">Não há finalidades cadastradas.</li>
        @endif
    </ul>

</div>
    
@endsection