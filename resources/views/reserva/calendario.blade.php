<div id="grafico" style="width:100%; overflow-x:auto; overflow-y:auto;"></div>
    <script>

    let reserva_grafico = [];
    let salas_aula = [];
    let finalidade = [];
    $(document).ready(function(){
        $("#form-search").on("submit", function(e) {
            e.preventDefault();
            $.ajax({
                url: '/calendario',
                type: $(this).attr('method'),
                data: $(this).serialize(),
                beforeSend: function(){
                    $("#spinner").removeClass('d-none');
                },
                success: function(response){
                    finalidade = response.finalidade_reserva;
                    reserva_grafico = response.reserva_grafico;
                    salas_aula = response.salas_aula;
                    $("#grafico").html(response);
                    $("#spinner").addClass('d-none');
                    iniciarGrafico();
                },
                error: function(error){
                    $("#grafico").html("<h3 class='text-danger' style='margin:20px;'>" + error.responseJSON.error + "</h3>");
                    $("#spinner").addClass('d-none');
                }
            });
        });
    });
    function iniciarGrafico(){
        const reservas = [];
        reserva_grafico.forEach((e, i) =>{
            const r = reserva_grafico[i];
            reservas.push({
                sala_id: r.sala_id,
                sala: r.nome_sala,
                inicio: r.horario_inicio,
                fim: r.horario_fim,
                descricao: r.nome,
                finalidade: r.cor,
                legenda_finalidade: r.legenda
            });
        });

        const cores = Object.keys(finalidade);
        const teste = Object.values(finalidade);
        let container = $("#finalidades");
        let item = [];
        let legenda = [];
        container.empty();
        cores.forEach((e,i) => {
            console.log(legenda);
            legenda = finalidade[e][0]['legenda'];
            item = $("<div>")
            .css({
                "background-color":e,
                "width":100,
                "height":100,
                "border-radius":5,
                "border":"1px solid black",
                "margin":20
            })
            .addClass("col")
            .text(legenda)
            .css({'font-weight':"bold",'font-size':16,"padding-top":35})
            container.append(item);
        });

        const margin = {
            top: 30,
            right: 40,
            bottom: 90,
            left: 150
        },

        //ajustes conforme tamanho da tela
        reservaHeight = salas_aula.length < 20 ? window.innerHeight : window.innerHeight * 3;
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
        if (salas.length === 0 && salas_aula.length > 0) {
            salas.push(salas_aula.map(c => c.nome));
        }

        const y = d3.scaleBand()
            .domain(salas_aula.map(c => c.nome)) //retorna todas as salas do prédio selecionado, livre ou não
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
                const sala = salas_aula.find(s => s.nome === d);
                const link = d3.select(this.parentNode);

                d3.select(this).remove();
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
    }
    
    </script>