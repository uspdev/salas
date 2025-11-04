<div class="col-md-12">
    <div class="card">
        <div 
        class="card-header"
        type="button" 
        data-toggle="collapse" 
        data-target="#collapse0" 
        aria-expanded="false" 
        aria-controls="collapse0">
        <h2><b>Programa de salas</b></h2><i class="fas fa-plus-square"></i>
        </div>
        <div class="collapse" id="collapse0" >
            <div class="card-body">
                <div class="row">
                    <div class="col-12">
                        @include('reserva.partials.form_calendario')
                    </div>
                    <hr/>
                    <div class="col-12">
                        <b>Legenda de cores</b>
                        <div id="finalidades" class="row justify-content-center"></div>
                    </div>
                </div>
                <div class="spinner-border m-5 d-none" role="status" id="spinner">
                    <span class="sr-only">Carregando...</span>
                </div>
                @include('reserva.calendario')
            </div>
        </div>
    </div>
</div>
<div class="col-md-12" style="margin-top:12px;">
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
</div>
<style>

@media(max-width: 1330px){
    .col-1{
        flex: 0 0 100%;
        max-width:100%;
    }
}

</style>