@extends('main')
@section('content')
    <form action="/calendario" method="get">
        <div class="col-4">
            <label><b>Escolha o prédio</b></label>
            <select name="categoria_id[]" class="select2 form-control">
                @foreach ($categorias as $categoria)
                    <option value="{{ $categoria->id }}" {{ in_array($categoria->id, $categoria_id) ? 'selected' : '' }}>
                        {{ $categoria->nome }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="col-4">
            <label><b>Escolha a data</b></label>
            <input type="text" class="datepicker form-control" name="data" value="{{ old('data', request()->data) }}">
            <small class="text-muted">Ex.: {{ $data->format('d/m/Y') }}</small>
        </div>
        <div class="col-4">
            <button type="submit" class="btn btn-success"><i class="fas fa-search"></i></button>
        </div>
    </form>


    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h2>Programa de salas</h2>
            </div>
            <div class="card-body">
                <div id="grafico"></div>
                <div class="tooltip" id="tooltip"></div>
            </div>
        </div>
    </div>

    <style>
        .card {
            box-shadow: 2px 2px 10px rgba(0, 0, 0, 0.3);
        }
    </style>


    <script>
        const dados = {
            res: @json($res),
            salas_aula: @json($salas)
        };

        const reservas = [];

        for (let i = 0; i < dados.res.length; i++) {
            const r = dados.res[i];

            reservas.push({
                sala_id: r.sala_id,
                sala: r.nome_sala ? r.nome_sala : '?',
                inicio: r.horario_inicio,
                fim: r.horario_fim,
                descricao: r.nome,
            });
        }

        let ReservaSize = dados.salas_aula.length <= 20 ? (window.innerHeight - 1280) : (window.innerHeight * 2);
        const margin = {
                top: 30,
                right: 40,
                bottom: 90,
                left: 150
            },
            //width = 1800 - margin.left - margin.right,
            width = (window.innerWidth);
        height = (window.innerHeight + ReservaSize) - margin.top - margin.bottom;

        const svg = d3.select("#grafico")
            .append("svg")
            //.attr("width", width + margin.left + margin.right)
            .attr("width", "100%")
            .attr("height", height + margin.top + margin.bottom)
            .append("g")
            .attr("transform", `translate(${margin.left},${margin.top})`);

        // ======================
        // ESCALAS
        // ======================
        const parseHora = d3.timeParse("%H:%M");

        const minHora = parseHora("8:00");
        const maxHora = parseHora("23:00");

        const x = d3.scaleTime()
            .domain([d3.timeHour.offset(minHora, 0), d3.timeHour.offset(maxHora, 2)]) // adiciona 1h de folga
            .range([2, width]);

        const salas = Array.from(new Set(reservas.map(d => d.sala)));

        if (salas.length === 0 && dados.salas_aula.length > 0) {
            salas.push(dados.salas_aula.map(c => c.nome));
        }

        const y = d3.scaleBand()
            //.domain(dados.res.map(c => c.nome_sala))
            .domain(dados.salas_aula.map(c => c.nome)) //retorna todas as salas do prédio selecionado, livre ou não
            .range([1, height])
            .padding(0.1);
        // ======================
        // EIXOS
        // ======================
        const eixoX = d3.axisBottom(x)
            .ticks(d3.timeHour.every(1))
            .tickFormat(d3.timeFormat("%H:%M"));
        const eixoY = d3.axisLeft(y);

        svg.append("g")
            .attr("transform", `translate(0, ${height})`)
            .attr("class", "eixo")
            .call(eixoX)
            .attr("font-size", "16px");

        svg.append("g")
            .attr("class", "eixo")
            .call(eixoY)
            .attr("font-size", "16px");

        // ======================
        // TOOLTIP
        // ======================
        const tooltip = d3.select("#tooltip");

        // ======================
        // BARRAS (RESERVAS)
        // ======================

        svg.selectAll(".barra")
            .data(reservas)
            .enter()
            .append("a")
            .attr("xlink:href", d => `/salas/${d.sala_id}`)
            .attr("target", "_blank")
            .append("rect")
            .attr("class", "barra")
            .attr("x", d => x(parseHora(d.inicio)))
            .attr("y", d => y(d.sala))
            .attr("width", d => x(parseHora(d.fim)) - x(parseHora(d.inicio)))
            .attr("height", y.bandwidth())
            .attr("fill", d => "#ffd53bff")
            .on("mouseover", function(event, d) {
                tooltip.style("opacity", 1)
                    .html(`
        <strong>${d.descricao}</strong><br>
        ${d.sala}<br>
        ${d.inicio} – ${d.fim}
      `);
                d3.select(this).attr("fill", "#ff4e4eff");
            })
            .on("mouseout", function() {
                tooltip.style("opacity", 0);
                d3.select(this).attr("fill", "#ffd53bff");
            });

        // ======================
        // RÓTULOS NAS BARRAS
        // ======================

        function truncarDescricao(descricao, limite) {
            if (!descricao) return "Sem descrição";
            return descricao.length > limite ? descricao.slice(0, limite) + "…" : descricao;
        }

        svg.selectAll(".label")
            .data(reservas)
            .enter()
            .append("text")
            .attr("x", d => x(parseHora(d.inicio)) + 5)
            .attr("y", d => y(d.sala) + y.bandwidth() / 1.6)
            .attr("fill", "#333")
            .attr("font-size", "12px")
            .text(d => `${truncarDescricao(d.descricao, 15)} (${d.inicio}-${d.fim})`);
    </script>
@endsection
@section('javascripts_bottom')
@endsection
<script src="https://d3js.org/d3.v7.min.js"></script>
