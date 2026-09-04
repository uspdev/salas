@extends('main')

@section('content')
    <style>
        .dot {
            height: 12px;
            width: 12px;
            border-radius: 50%;
            display: inline-block;
        }
    </style>

    @can('admin')
        <a href="{{ route('salas.create') }}" class="btn btn-success mb-3">Cadastrar Sala</a>
    @endcan

    <div class="card">
        <div class="card-header"><b>Salas</b></div>
        <div class="card-body">
            <p>Ao excluir esta sala, todas as reservas listadas abaixo serão juntamente excluídas. Deseja continuar?</p>
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Data</th>
                            <th>Horário</th>
                            <th>Sala</th>
                            <th>Categoria</th>
                            <th>Finalidade</th>
                            <th>Título</th>
                            <th>Capacidade</th>
                            <th>Descrição</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($reservas as $reserva)
                            <tr>
                                <td>{{ $reserva->data }}</td>
                                <td>{{ $reserva->horario_inicio }} - {{ $reserva->horario_fim }}</td>
                                <td><a href="/salas/{{ $reserva->sala_id }}">{{ $reserva->sala->nome }}</a></td>
                                <td>{{ $reserva->sala->categoria->nome }}</td>
                                <td>
                                    <div class="dot ml-4"
                                        style="background-color: {{ $reserva->status == 'pendente' ? config('salas.cores.pendente') : $reserva->finalidade->cor ?? config('salas.cores.semFinalidade') }};">
                                    </div>
                                </td>
                                <td><a href="/reservas/{{ $reserva->id }}">{{ $reserva->nome }}</a></td>
                                <td>{{ $reserva->sala->capacidade }} pessoas</td>
                                <td>{{ $reserva->descricao }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @can('admin')
                <form method="POST" action="{{ route('salas.delete', $sala->id) }}" class="d-inline">
                    @csrf
                    @method('delete')
                    <button type="submit" class="btn btn-danger" onclick="return confirm('Tem certeza?');">
                        EXCLUIR
                    </button>
                </form>
            @endcan
        </div>
    </div>
@endsection

@section('javascripts_bottom')
    <script>
        $(document).ready(function() {
            $('#btn-limpar-filtros').on('click', function() {
                $('#form-filtros').find(':input').val("");
                $('.select2').val('val').trigger('change');
            });
        });
    </script>
@endsection
