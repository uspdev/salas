# *Endpoints* e *Responses* da API

- `/api/v1/salas`: retorna todas as salas cadastradas no sistema com suas informações.

Exemplo de _response_:
```json
{
    "data": [{
        "id": 1,
        "nome": "Sala 01",
        "id_categoria": 1,
        "categoria": "Prédio Principal",
        "capacidade": 40,
        "recursos": []
    },
    // ...
    , {
        "id": 16,
        "nome": "Auditório",
        "id_categoria": 4,
        "categoria": "Bloco C",
        "capacidade": 100,
        "recursos": ["Projetor", "Lousa Interativa"]
    }]
}
```

---

- `/api/v1/salas/{sala_id}`: retorna as informações de uma sala.

Exemplo de _response_:
```json
{
    "data": {
        "id": 16,
        "nome": "Auditório",
        "id_categoria": 4,
        "categoria": "Bloco C",
        "capacidade": 100,
        "recursos": ["Projetor", "Lousa Interativa"]
    }
}
```

---

- `/api/v1/categorias`: retorna todas as categorias cadastradas no sistema.

Exemplo de _response_:
```json
{
    "data": [{
        "id": 1,
        "nome": "Prédio Principal"
    }, {
        "id": 2,
        "nome": "Bloco A"
    }, {
        "id": 3,
        "nome": "Bloco B"
    }, {
        "id": 4,
        "nome": "Bloco C"
    }]
}
```

---

- `/api/v1/categorias/{categoria_id}`: retorna as informações de uma categoria.

Exemplo de _response_:
```json
{
    "data": {
        "id": 1,
        "nome": "Prédio Principal",
        "vinculo_cadastrado": "Pessoas da unidade",
        "setores_cadastrados": ["Assistência Técnica Administrativa", "Serviços Gerais"],
        "salas": [{
            "id": 1,
            "nome": "Sala 01",
            "id_categoria": 1,
            "categoria": "Prédio Principal",
            "capacidade": 40,
            "recursos": []
        }, {
            "id": 2,
            "nome": "Sala 02",
            "id_categoria": 1,
            "categoria": "Prédio Principal",
            "capacidade": 40,
            "recursos": []
        }, {
            "id": 3,
            "nome": "Sala 03",
            "id_categoria": 1,
            "categoria": "Prédio Principal",
            "capacidade": 40,
            "recursos": ["Lousa Interativa", "Projetor"]
        }]
    }
}
```

---

- `/api/v1/finalidades`: retorna todas as finalidades cadastradas no sistema.

Exemplo de _response_:
```json
{
    "data": [{
        "id": 1,
        "legenda": "Graduação"
    }, {
        "id": 2,
        "legenda": "Pós-Graduação"
    }, {
        "id": 3,
        "legenda": "Especialização"
    }, {
        "id": 4,
        "legenda": "Extensão"
    }, {
        "id": 5,
        "legenda": "Defesa"
    }, {
        "id": 6,
        "legenda": "Qualificação"
    }, {
        "id": 7,
        "legenda": "Reunião"
    }, {
        "id": 8,
        "legenda": "Evento"
    }]
}
```

---

- `/api/v1/reservas`: retorna todas as reservas do dia corrente.

Exemplo de _response_:
```json
{
    "data": [{
        "nome": "Recepção da Pós-Graduação",
        "sala": "Sala 01",
        "sala_id": 1,
        "data": "15/08/2024",
        "horario_inicio": "9:00",
        "horario_fim": "12:00",
        "finalidade": "Pós-Graduação",
        "descricao": "Recepção dos novos alunos da pós-graduação.",
        "status": "aprovada",
        "cadastrada_por": "João da Silva",
        "responsaveis": ["Silvério Rocha", "Armando de Souza"]
    }, {
        "nome": "Aula Magna",
        "sala": "Auditório",
        "sala_id": 16,
        "data": "15/08/2024",
        "horario_inicio": "15:00",
        "horario_fim": "16:40",
        "finalidade": "Graduação",
        "descricao": "Aula magna aberta para todos os ingressantes",
        "status": "pendente",
        "cadastrada_por": "Fulano de Andrade",
        "responsaveis": ["Fulano de Andrade"]
    }, {
        "nome": "Recepção da Graduação",
        "sala": "Sala 01",
        "sala_id": 1,
        "data": "15/08/2024",
        "horario_inicio": "19:20",
        "horario_fim": "21:00",
        "finalidade": "Graduação",
        "descricao": "Recepção dos novos alunos do período noturno da graduação.",
        "status": "aprovada",
        "cadastrada_por": "Ciclano de Moura",
        "responsaveis": ["Ciclano de Moura"]
    }]
}
```

---

- `/api/v1/reservas/finalidades/{finalidade_id}`: retorna todas as reservas do dia corrente com uma determinada finalidade.

Exemplo de _response_:
```json
{
    "data": [{
        "nome": "Aula Magna",
        "sala": "Auditório",
        "sala_id": 16,
        "data": "15/08/2024",
        "horario_inicio": "15:00",
        "horario_fim": "16:40",
        "finalidade": "Graduação",
        "descricao": "Aula magna aberta para todos os ingressantes",
        "status": "pendente",
        "cadastrada_por": "Fulano de Andrade",
        "responsaveis": ["Fulano de Andrade"]
    }, {
        "nome": "Recepção da Graduação",
        "sala": "Sala 01",
        "sala_id": 1,
        "data": "15/08/2024",
        "horario_inicio": "19:20",
        "horario_fim": "21:00",
        "finalidade": "Graduação",
        "descricao": "Recepção dos novos alunos do período noturno da graduação.",
        "status": "aprovada",
        "cadastrada_por": "Ciclano de Moura",
        "responsaveis": ["Ciclano de Moura"]
    }]
}
```

---


- `/api/v1/reservas/salas/{sala_id}`: retorna todas as reservas do dia corrente em uma determinada sala.

Exemplo de _response_:
```json
{
    "data": [{
        "nome": "Recepção da Pós-Graduação",
        "sala": "Sala 01",
        "sala_id": 1,
        "data": "15/08/2024",
        "horario_inicio": "9:00",
        "horario_fim": "12:00",
        "finalidade": "Pós-Graduação",
        "descricao": "Recepção dos novos alunos da pós-graduação.",
        "status": "aprovada",
        "cadastrada_por": "João da Silva",
        "responsaveis": ["Silvério Rocha", "Armando de Souza"]
    }, {
        "nome": "Recepção da Graduação",
        "sala": "Sala 01",
        "sala_id": 1,
        "data": "15/08/2024",
        "horario_inicio": "19:20",
        "horario_fim": "21:00",
        "finalidade": "Graduação",
        "descricao": "Recepção dos novos alunos do período noturno da graduação.",
        "status": "aprovada",
        "cadastrada_por": "Ciclano de Moura",
        "responsaveis": ["Ciclano de Moura"]
    }]
}
```

---