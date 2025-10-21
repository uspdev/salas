@extends('main')
@section('content')
    <form action="calendario" method="get">
        <div class="col-md-4">
            <label><b>Selecione o prédio</b></label>
            <select name="categorias_id[]" class="form-control">
                @foreach ($categorias as $categoria)
                    <option value="{{ $categoria->id }}" {{ in_array($categoria->id, $categorias_id) ? 'selected' : '' }}>
                        {{ $categoria->nome }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="col-md-2" style="margin-top:8px; margin-bottom:8px;">
            <label><b>Selecione a data</b></label>
            <input value="{{ old('data', request()->data) }}" type="text" name="data" class="datepicker form-control">
            <small class="text-muted">Formato: {{ $dataSelecionada->format('d/m/Y') }}</small>
        </div>
        <div class="col-md-4" style="margin-bottom:8px;">
            <button class="btn btn-success" type="submit"><i class="fas fa-search"></i></button>
        </div>
    </form>
    <div class="row">
        <div class="table-responsive">
            <table class="table" border="1" style="border-collapse: collapse; width:100%; text-align:center;">
                <thead>
                    <tr>
                        <th style="padding:12px;">Sala</th>
                        @foreach ($horas as $hora)
                            <th>{{ $hora }}:00</th>
                        @endforeach
                    </tr>
                </thead>
                <tbody>
                    @foreach ($salas as $sala)
                        <tr>
                            <th><a href="salas/{{ $sala->id }}">{{ $sala->nome }}</a></th>

                            @foreach ($horas as $hora)
                                @php
                                    $inicio = $dataSelecionada->copy()->setTime($hora, 0, 0);
                                    $fim = $inicio->copy()->addHour();

                                    $ocupada = \App\Models\Reserva::where('sala_id', $sala->id)
                                        ->whereDate('data', $dataSelecionada)
                                        ->where(function ($query) use ($inicio, $fim) {
                                            $query->whereRaw(
                                                "STR_TO_DATE(horario_inicio, '%H:%i') < STR_TO_DATE('{$fim->format('H:i',)}', '%H:%i')
                                                AND STR_TO_DATE(horario_fim, '%H:%i') >  STR_TO_DATE('{$inicio->format('H:i',)}','%H:%i')",
                                            );
                                        })
                                        ->exists();
                                @endphp

                                <td style="background-color: {{ $ocupada ? '#f88' : '#8f8' }}">
                                    {{ $ocupada ? 'Ocupada' : '' }}
                                </td>
                            @endforeach
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection
@section("javascripts_bottom")
@endsection
