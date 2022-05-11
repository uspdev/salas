@extends('main')

@section('title') 
    Sistema de Reserva de Salas 
@endsection

@section('content')
    <div class="card">
        <div class="card-header">   
            <h5 class="card-title">Você quis dizer...</h5>
        </div>
        <div class="card-body">
            <table class="table">
                <tbody>
                    @foreach($salas as $sala)
                        <tr>
                            <td><a href="/salas/{{ $sala->id }}">{{ $sala->nome }}</a></td>
                            <td>{{ $sala->categoria->nome }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection