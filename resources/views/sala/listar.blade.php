@extends('main')

@section('content')
    <a href="{{route('salas.create')}}" class="btn btn-success mb-3">Cadastrar Sala</a>
    <div class="card">
        <div class="card-header"><b>Salas</b></div>
        <div class="card-body">
            @foreach ($salas as $sala)
                <ul class="list-group mb-2">
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <a href="{{route('salas.edit', $sala->id)}}">{{$sala->nome}}</a>
                        <div class="d-inline-flex">
                            <a href="{{route('salas.edit', $sala->id)}}" class="btn btn-warning text-white mr-1"> <i class="fas fa-pen"></i> </a>
                            <form action="{{route('salas.destroy', $sala->id)}}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger" onclick="return confirm('Tem certeza?');">
                                    <i class="fa fa-trash" ></i>
                                </button>
                            </form>
                        </div>
                    </li>
                </ul>
            @endforeach
        </div>
    </div>
@endsection