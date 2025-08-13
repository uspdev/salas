@extends('main')
@section('content')
    <div class="card">
        <div class="card-header"><b>Procurar por salas disponíveis</b></div>
        <div class="card-body">
            <div class="row">
                <div class="col">
                    <label>Data</label>
                    <input type="text" class="datepicker form-control" type="text" name="data" placeholder="Ex.: 08/09/2025">
                </div>
                <div class="col">
                    <label>Horário início</label>
                    <input type="text" name="horario_inicio" class="form-control">
                    <small class="text-muted">Formato 9:00</small>
                </div>
                <div class="col">
                    <label>Horário fim</label>
                    <input type="text" name="horario_fim" class="form-control">
                    <small class="text-muted">Formato 9:00</small>
                </div>
                <div class="col" style="margin-top:30px;">
                    <button name="botao" type="submit" class="btn btn-success"><i class="fas fa-search"></i></button>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header"><b>Salas disponíveis</b></div>
        <div class="card-body" id="div"></div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            $('button[name="botao"]').on('click', function() {
                let horario_inicio = $('input[name="horario_inicio"]');
                let horario_fim = $('input[name="horario_fim"]');
                let data = $('input[name="data"]');
                let div = document.getElementById('div');
                let view = '';

                $.ajax({
                    'url': 'salas_livres/search',
                    'type': 'GET',
                    'data': {
                        data: data.val(),
                        horario_inicio: horario_inicio.val(),
                        horario_fim: horario_fim.val()
                    },
                    success: function(response) {
                        console.log(response);
                        if(response == 400){
                            view = `<div class="alert alert-danger">Informe o horário no formato h:mm</div>`;
                            document.getElementById('div').innerHTML = view;
                        }
                        if(response == 404){
                            view = `<div class="alert alert-danger">Nenhuma sala encontrada para o filtro solicitado</div>`;
                            document.getElementById('div').innerHTML = view;
                        }
                        if (response.length > 0) {
                            response.forEach((e) => {
                                view += `  
                                    <p>Sala: <a href="/salas/${e.id}">${e.nome}</a> da ${e.nomcat} - capacidade: ${e.capacidade} pessoas </p>
                                `;
                            });
                            document.getElementById('div').innerHTML = view;
                        }
                    }
                });
            });
        });
    </script>
@endsection