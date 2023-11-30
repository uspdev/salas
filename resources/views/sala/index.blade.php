@extends('main')
@section('title')
    Sistema de Reserva de Salas
@endsection
@section('styles')
    <style>
        #categoria-header {
            display: flex;
            justify-content: space-between;
        }
    </style>
@endsection

@section('content')
    <div class="card">
        <div class="card-header">
            <h5 class="card-title">Calendário por Sala</h5>
        </div>
        <div class="card-body">
            <div class="mb-2" style="padding: 0 1.25rem 0 1.25rem !important">
                <select id="select-salas" class="form-control" name="sala">
                   <option></option>
                   @foreach($categorias as $categoria)
                     <optgroup label="{{ $categoria->nome }}">
                       @foreach($categoria->salas as $sala)
                        <option value="{{ $sala->id }}">{{ $sala->nome }}</option>
                       @endforeach
                     </optgroup>
                   @endforeach
                </select>
            </div>
            <ul class="list-group list-group-flush">
                @foreach($categorias as $categoria)
                <li class="list-group-item">
                    <div class="card">
                        <div class="card-header" id="categoria-header" type="button" data-toggle="collapse" data-target="#collapse{{ $categoria->id }}" aria-expanded="false" aria-controls="collapse{{ $categoria->id }}">
                            @can('admin')
                                <a href="/categorias/{{ $categoria->id }}">{{ $categoria->nome }}</a>
                            @else
                                {{ $categoria->nome }}
                            @endcan
                            <i class="far fa-plus-square"></i>
                        </div>
                        <ul class="list-group list-group-flush">
                            <div class="collapse" id="collapse{{ $categoria->id }}">
                                <div class="card-body">
                                    @include('sala.partials.table', ['salas' => $categoria->salas])
                                </div>
                            </div>
                        </ul>
                    </div>
                </li>
                @endforeach
            </ul>
        </div>
    </div>
@endsection

@section('javascripts_bottom')
    <script>
        // Para ficar com foco no select2 ao abri-lo. Referência: https://stackoverflow.com/questions/68030101/why-is-jquery-select2-autofocus-not-working-and-how-do-i-fix-it
        $(document).on('select2:open', () => {
            document.querySelector('.select2-search__field').focus();
        });

        $(document).ready(function() {
            $('#select-salas').select2({
                theme: "bootstrap4",
                placeholder: "Buscar sala"
            });
        });

        $('#select-salas').on('change', function(e) {
            window.location.replace('{{route('salas.index')}}/' + e.target.value);
        })
    </script>
@endsection
