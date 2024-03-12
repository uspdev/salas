@extends('main')
@section('content')

@can ('admin')
<div class="mb-3">
    <a href="{{route('finalidades.create')}}" class="btn btn-success">Adicionar Finalidade</a>
</div>
@endcan

<div class="card">
    <div class="card-header">
        Lista de Finalidades
    </div>
    <ul class="list-group list-group-flush">
        @if (count($finalidades) > 0)
            @foreach ($finalidades as $finalidade)
                <li class="list-group-item d-inline-flex justify-content-between align-items-center">
                    <a href="{{route('finalidades.edit', $finalidade->id)}}" style="background-color: {{$finalidade->cor}}" class="btn px-4 rounded">
                        {{$finalidade->legenda}} 
                    </a>
                    <div class="row">
                        <a href="{{route('finalidades.edit', $finalidade->id)}}" class="btn btn-warning mr-2"><i class="fa fa-pen text-white"></i></a>

                        <form action="{{route('finalidades.destroy', $finalidade->id)}}" method="POST">
                            @csrf
                            @method('DELETE')

                            <!-- Modal Button -->
                            <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#delete-{{$finalidade->legenda}}-Modal"><i class="fa fa-trash" ></i></button>

                            <!-- Modal -->
                            <div class="modal fade" id="delete-{{$finalidade->legenda}}-Modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="exampleModalLabel">Excluir a finalidade {{$finalidade->legenda}}?</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <p class="text-justify">
                                        Se excluir esta finalidade todas as reservas que estiveram com esta finalidade selecionada ficarão sinalizadas da seguinte forma:
                                    </p>
                                    <div class="rounded p-2 m-auto w-50" style="background-color: {{config('salas.cores.semFinalidade')}}">Sem finalidade</div>
                                </div>
                                <div class="modal-footer">
                                    <button type="submit" class="btn btn-danger">Excluir</button>
                                    <button type="button" class="btn btn-light" data-dismiss="modal">Fechar</button>
                                </div>
                                </div>
                            </div>
                            </div>
                        </form>
                    </div>
                </li>
            @endforeach
        @else
            <li class="list-group-item">Não há finalidades cadastradas.</li>
        @endif
    </ul>

</div>
    
@endsection