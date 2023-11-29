@extends('main')
@section('content')
<div class="card">
    <div class="card-header">
        <b>Recursos</b>
    </div>
    <div class="card-body">
    @include('recurso.partials.form')
    <br>
    @forelse($recursos as $recurso)
        <ul class="list-group mb-2">
            <li class="list-group-item" style="display: flex; justify-content: space-between;">    
                {{ $recurso->nome }}
                <form method="POST" action="/recursos/{{ $recurso->id }}">
                    @csrf
                    @method('delete')
                    <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Tem certeza?');">
                        <i class="fa fa-trash" ></i>
                    </button>
                </form>
            </li>
        </ul>
    @empty
        Ainda não há recursos cadastrados.
    @endforelse

    </div>
</div>
<br>
@endsection