@extends('main')
@section('content')
    <div class="card">
        <div class="card-header"><b>Adicionar Finalidade</b></div>
        <div class="card-body">
            <form action="{{route('finalidades.store')}}" method="POST">
                @csrf
                <div class="row">
                    <div class="form-group col-sm">
                        <b>Legenda</b>
                        <input name="legenda" type="text" class="form-control" required>
                    </div>
                    <div class="form-group col-sm">
                        <b>Cor</b>
                        <input name="cor" type="color" class="form-control form-control-color" title="Escolha a cor da finalidade" required>
                    </div>
                </div>
                <button type="submit" class="btn btn-success">Enviar</button>
            </form>
        </div>
    </div>
@endsection