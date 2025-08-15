@extends('main')
@section('content')
    <div class="card">
        <div class="card-header"><b>Procurar por salas disponíveis</b></div>
        <div class="card-body">
            <div class="row">
                <div class="col">
                    <label>Data</label>
                    <input type="text" class="datepicker form-control" id="data" type="text" name="data">
                    <small class="text-muted">Ex.: {{$today}}</small>
                </div>
                <div class="col">
                    <label>Horário início</label>
                    <input type="text" name="horario_inicio" id="horario_inicio" class="form-control">
                    <small class="text-muted">Formato 9:00</small>
                </div>
                <div class="col">
                    <label>Horário fim</label>
                    <input type="text" name="horario_fim" id="horario_fim" class="form-control">
                    <small class="text-muted">Formato 9:00</small>
                </div>
                <div class="col" style="margin-top:30px;">
                    <button name="botao" id="botao" type="submit" class="btn btn-success"><i class="fas fa-search"></i></button>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header"><b>Salas disponíveis</b></div>
        <div class="card-body" id="div"></div>
    </div>

    @section('javascripts_bottom')
        <script>
            $(document).ready(function(){
                $('#botao').on('click', function() {
                    let view = '';

                    $.ajax({
                        'url': 'salas_livres/search',
                        'type': 'GET',
                        'data': {
                            data: $('#data').val(),
                            horario_inicio: $('#horario_inicio').val(),
                            horario_fim: $('#horario_fim').val()
                        },
                        success: function(response) {
                            if(response['status'] != 200){
                                view = `<div class="alert alert-danger">${response['content']}</div>`;
                                $("#div").html(view);
                            }
                            response['content'].forEach((sala, i) => {
                                view += `  
                                    <p><a href="/salas/${sala.id}">${sala.nome}</a> da <b>${sala.nomcat}</b> - capacidade: ${sala.capacidade} pessoas </p>
                                `;
                            });
                            $("#div").html(view);
                        }
                    });
                });
            });
        </script>
    @endsection
@endsection