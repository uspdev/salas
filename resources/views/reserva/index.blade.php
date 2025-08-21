<div class="card">
    <div class="card-body">
        <form method="GET" action="/search" id="form-filtros">
            <b>Categorias:</b>
            <select name="filter[]" class="select2 form-control" multiple="multiple">
                @foreach($categorias as $categoria)
                    <option value="{{$categoria->id}}" {{ in_array($categoria->id, $filter) ? 'selected' : ''}}>{{$categoria->nome}}</option>
                @endforeach
            </select>

            <br>
            <b>Finalidades:</b>
            <select name="finalidades_filter[]" id="" class="select2 form-control" multiple="multiple">
                @foreach ($finalidades as $finalidade)
                    <option value="{{$finalidade->id}}" {{in_array($finalidade->id, $finalidades_filter) ? 'selected' : ''}}>{{$finalidade->legenda}}</option>
                @endforeach
            </select>

            <br>
            <b>Salas:</b>
            <select name="salas_filter[]" id="" class="select2 form-control" multiple="multiple">
                @foreach ($salas as $sala)
                    <option value="{{$sala->id}}" {{in_array($sala->id, $salas_filter) ? 'selected' : ''}}>{{$sala->nome}}</option>
                @endforeach
            </select>

            <br>
            <div class="row">
                <div class="col-1">
                    <b>Data:</b>
                    <input type="text" class="datepicker form-control" id="input_busca_data" name="busca_data" type="text" value="{{ request()->busca_data ?? '' }}">      
                </div>
                <div class="col-sm">
                    <b>Título da Reserva:</b>
                    <input type="text" class="form-control" id="input_busca_nome" name="busca_nome" type="text" value="{{ request()->busca_nome }}">
                </div>
            </div>
            <br>
            <div class="row">
                <div class=" col-sm input-group">
                    <span class="input-group-btn">
                        <button id="buscar_reservas" type="submit" class="btn btn-success"> Buscar </button>
                        <a class="btn btn-secondary" id="btn-limpar-filtros">Limpar</a>
                    </span>      
                </div>
            </div>
        </form>
        <br>
        {{ $reservas->appends(request()->except('page'))->links() }}
        @include('reserva.partials.table')
    </div>
</div>

<style>

@media(max-width: 1330px){
    .col-1{
        flex: 0 0 100%;
        max-width:100%;
    }
}

</style>