<div class="card">
    <div class="card-header">
        Adicionar recurso
    </div>
    <div class="card-body">
        <form method="POST" action="/recursos">
            @csrf
            <div class="row justify-content-around">
                <div class="col">
                    <input class="form-control" type="text" name="nome" value="{{  old('nome', request()->nome) ?? '' }}" >
                </div>
                <div class="col-1">
                    <button type="submit" class="btn btn-success"> Enviar </button> 
                </div>
            </div>    
        </form>      
    </div>
</div>