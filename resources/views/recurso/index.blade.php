@extends('main')
@section('content')
<a href="{{route('recursos.create')}}" class="btn btn-success mb-3">Cadastrar Recurso</a>
<div class="card">
    <div class="card-header">
        <b>Recursos</b>
    </div>
    <div class="card-body">
    @forelse($recursos as $recurso)
        <ul class="list-group mb-2">
            <li class="list-group-item d-flex justify-content-between align-items-center">    
                <a href="{{route('recursos.edit', $recurso->id)}}">{{ $recurso->nome }}</a>
                <div class="d-inline-flex">
                    <a href="{{route('recursos.edit', $recurso->id)}}" class="btn btn-warning text-white mr-1"> <i class="fas fa-pen"></i> </a>
                    <form method="POST" action="/recursos/{{ $recurso->id }}">
                        @csrf
                        @method('delete')
                        <button type="submit" class="btn btn-danger btn" onclick="return confirm('Tem certeza?');">
                            <i class="fa fa-trash" ></i>
                        </button>
                    </form>
                </div>
            </li>
        </ul>
    @empty
        Ainda não há recursos cadastrados.
    @endforelse

    </div>
</div>
<br>
@endsection