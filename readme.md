# Sistema Salas

Sistema em Laravel para o gerenciamento dos eventos de um instituto de acordo com as salas e horários disponíveis.

# Recursos

- Visualização do calendário de reservas de cada sala (semanal, diário e mensal)
- Busca pelas reservas filtrando por categoria, data e título
- Administração dos usuários permitidos para reservar em um grupo de salas através das Categorias
- Criação e edição de reservas em massa caso haja repetição do evento
- Validação para que reservas não sejam cadastradas caso haja sobreposição de data, horário e sala

# Como subir a aplicação
## Instalação

```sh
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate:fresh --seed
```

## Para acessar a aplicação

```sh
php artisan serve
```
