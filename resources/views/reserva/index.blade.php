<div class="card">
    <div class="card-body">
        <form method="GET" action="/search">
            <b>Categorias:</b>
            <div class="row mt-2">
                @foreach($categorias as $categoria)
                    <div class="form-group col-md-3">
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="checkbox" value="{{ $categoria->id }}" id="inlineCheckbox{{ $categoria->id }}" name="filter[]" @if(in_array($categoria->id, $filter))  checked  @endif/>
                            <label class="form-check-label" for="inlineCheckbox{{ $categoria->id }}">{{ $categoria->nome }}</label>
                        </div>
                    </div>
                @endforeach
            </div>
            <b>Finalidades:</b>
            <div class="row mt-2">
                @foreach ($finalidades as $finalidade)
                    <div class="form-group col-md-3">
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="checkbox" value="{{ $finalidade->id }}" id="inlineFinalidadeCheckbox{{ $finalidade->id }}" name="finalidades_filter[]" @if(in_array($finalidade->id, $finalidades_filter))  checked  @endif/>
                            <label class="form-check-label" for="inlineFinalidadeCheckbox{{ $finalidade->id }}">{{ $finalidade->legenda }}</label>
                        </div>
                    </div>
                @endforeach
            </div>
            <div class="row">
                <div class="col-sm input-group">
                    <input type="text" class="datepicker" id="input_busca_data" name="busca_data" type="text" placeholder="Data" value="{{ request()->busca_data ?? '' }}">      
                    <input type="text" class="form-control" id="input_busca_nome" name="busca_nome" type="text" placeholder="Título da reserva" value="{{ request()->busca_nome }}" style="margin-left: 1%;">
                </div>                 
            </div>
            <br>
            <div class="row">
                <div class=" col-sm input-group">
                    <span class="input-group-btn">
                        <button type="submit" class="btn btn-success"> Buscar </button>
                    </span>      
                </div>
            </div>
        </form>
        <br>
        {{ $reservas->appends(request()->except('page'))->links() }}
        @include('reserva.partials.table')
    </div>
</div>