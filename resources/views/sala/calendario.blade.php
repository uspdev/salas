<form action="/search" method="get">
        <div class="col-sm-6">
            <label><b>Esolha o prédio</b></label>
            <select name="categoria_id[]" class="select2 form-control">
                @foreach ($categorias as $categoria)
                    <option value="{{ $categoria->id }}" {{ in_array($categoria->id, $categoria_id) ? 'selected' : '' }}>
                        {{ $categoria->nome }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="col-sm-5">
            <label><b>Escolha a data</b></label>
            <input type="text" class="datepicker form-control" name="data" value="{{ old('data', request()->data) }}">
            <small class="text-muted">Ex.: {{ $data->format('d/m/Y') }}</small>
        </div>
        <div class="col-sm-1">
            <button type="submit" class="btn btn-success"><i class="fas fa-search"></i></button>
        </div>
    </div>

    <div class="col-md-12">
        <div class="card" id="card">
            <div class="card-header">
                <h2><b>Programa de salas</b></h2>
                <i class="fas fa-plus-square"></i>
            </div>
                <div class="card-body">
                    <div class="row justify-content-center" style="margin-bottom:15px;">
                        <div class="col-12">
                        <p><b>Legenda das cores</b></p>
                        </div>
                        @foreach($finalidade_reserva as $cor => $finalidade)
                            <div class="col">
                                <div 
                                style="color:black;
                                background-color:{{$cor}}; 
                                border:none; 
                                padding:15px;
                                border-radius:5px;
                                border:1px solid black;
                                text-align:center;
                                cursor:auto;">
                                {{$finalidade[0]['legenda']}}
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <hr/>
                    <div id="grafico" style="width:100%; overflow-x:auto; overflow-y:auto;">
                    </div>
                    <div class="tooltip" id="tooltip"></div>
                </div>
        </div>
    </div>

    <script>
        const dados = {
            reservas: @json($reserva_grafico),
            salas_aula: @json($salas_aula)
        };

        const reservas = [];
        dados.reservas.forEach((e, i) =>{
            const r = dados.reservas[i];
            reservas.push({
                sala_id: r.sala_id,
                sala: r.nome_sala,
                inicio: r.horario_inicio,
                fim: r.horario_fim,
                descricao: r.nome,
                finalidade: r.cor,
            });
        });

        const margin = {
            top: 30,
            right: 40,
            bottom: 90,
            left: 150
        },

        //ajustes conforme tamanho da tela
        reservaHeight = dados.salas_aula.length < 20 ? window.innerHeight : window.innerHeight * 3;
        height = (reservaHeight) + margin.bottom; 
        width = window.innerWidth < 768 ? window.innerWidth * 3 : window.innerHeight * 2; 

        const svg = d3.select("#grafico")
            .append("svg")
            .attr("width", width)
            .attr("height", height + margin.bottom)
            .append("g")
            .attr("transform", `translate(${margin.left},${margin.top})`);

        /* ESCALAS*/
        const parseHora = d3.timeParse("%H:%M");
        const minHora = parseHora("8:00");
        const maxHora = parseHora("23:59");

        const x = d3.scaleTime()
            .domain([d3.timeHour.offset(minHora, 0), d3.timeHour.offset(maxHora, 1)])
            .range([2, width]);

        const salas = Array.from(new Set(reservas.map(d => d.sala)));

        //retorna o nome das salas caso não haja nenhuma reserva
        if (salas.length === 0 && dados.salas_aula.length > 0) {
            salas.push(dados.salas_aula.map(c => c.nome));
        }

        const y = d3.scaleBand()
            .domain(dados.salas_aula.map(c => c.nome)) //retorna todas as salas do prédio selecionado, livre ou não
            .range([1, height])
            .padding(0.1);
        
        /* EIXOS */
        const eixoX = d3.axisBottom(x)
            .ticks(d3.timeHour.every(1))
            .tickFormat(d3.timeFormat("%H:%M"));
        const eixoY = d3.axisLeft(y);

        svg.append("g")
            .attr("transform", `translate(0, ${height})`)
            .attr("class", "eixo")
            .call(eixoX)
            .attr("font-size", "16px");

        const gY = svg.append("g").call(eixoY);

        gY.selectAll(".tick text")
            .each(function(d) {
                const sala = dados.salas_aula.find(s => s.nome === d);
                const link = d3.select(this.parentNode);

                d3.select(this).remove();
                console.log(sala.nome.length);
                link.append("a")
                    .attr("href", `/salas/${sala.id}`)
                    .attr("target", "_blank")
                    .append("text")
                    .attr("x", -10)
                    .attr("y", 5)
                    .attr("text-anchor", "end")
                    .attr("font-size", d => sala.nome.length > 15 ? "14px" : "16px")
                    .attr("fill","#0000ee99")
                    .style("cursor","pointer")
                    .text(sala.nome.length > 16 ? sala.nome.slice(0, 18) + "…" : sala.nome);
            });

        /* BARRAS */
        svg.selectAll(".barra")
            .data(reservas)
            .enter()
            .append("a")
            .attr("href", d => `/salas/${d.sala_id}`)
            .attr("target", "_blank")
            .append("rect")
            .attr("class", "barra")
            .attr("stroke","#333")
            .attr("stroke-width",".5")
            .attr("x", d => x(parseHora(d.inicio)))
            .attr("y", d => y(d.sala))
            .attr("width", d => {
                const barra = x(parseHora(d.fim)) - x(parseHora(d.inicio));
                d.comprimentoBarra = barra;
                return barra;
            })
            .attr("height", y.bandwidth())
            .attr("fill", d => d.finalidade);
        

        /* MEDIA QUERY (DISPOSITIVOS MÓVEIS) */
        const mediaQuery = window.matchMedia("(max-width: 768px)");
        if (mediaQuery.matches) { //somente telas menores a 768px
        d3.select("#grafico svg")
            .attr("width", 1200)
            .attr("height", height + margin.bottom);
        }

        /* LIMITE DE CARACTERES NAS BARRAS */
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
            .text(d => `${truncarDescricao(d.descricao, Math.floor(d.comprimentoBarra / 9))} (${d.inicio}-${d.fim})`);
    </script>

@section('javascripts_bottom')
@endsection
<script src="https://d3js.org/d3.v7.min.js"></script>
