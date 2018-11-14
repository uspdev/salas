reserva-sala

Frontend que implementa regras para reserva de salas. Suporta
como backend o [booked](https://www.bookedscheduler.com).


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
