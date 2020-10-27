# reserva-sala

Frontend que implementa regras para reserva de salas. Suporta
como backend o [booked](https://www.bookedscheduler.com).

## Instalação

```bash
composer install
cp .env.example .env
```

Crie a base de dados de acordo com o sue SGBD

No arquivo .env:
- Adicione as credenciais de acesso a API do Booked Scheduler
- Adicione as credenciais do seu SGBD

```bash
php artisan key:generate
php artisan migrate
```

Para acessar a aplicação

```bash
php artisan serve
```

## Rotas

Listas em json:
```bash
/api/salas
/api/agendas
```

## Dicas

Para verificar se o booked está recebendo requisições, tente:

    $ wget -qO- --post-data '{"username":"admin","password":"admin"}' http://server/phpScheduleIt/Web/Services/Authentication/Authenticate
    {
        "sessionToken": "513fc3a93bf02",
        "userId": "2",
        "isAuthenticated": true,
    }

    $ wget -qO- --header 'X-phpScheduleIt-SessionToken: 513fc3a93bf02' --header 'X-phpScheduleIt-UserId: 2' http://server/phpScheduleIt/Web/Services/Resources/4
    {
	    "resourceId": "4",
    }
