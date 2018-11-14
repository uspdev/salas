<?php

namespace App\Booked;

use GuzzleHttp\Client;


class Booked
{
    private $session;

    public function __construct()
    {
        $client = new Client();
        $res = $client->request('POST','http://localhost:8001/Web/Services/index.php/Authentication/Authenticate', [
            'json' => [
                'username' => 'admin',
                'password' => 'admin123',
            ],
        ]);

        $this->session = json_decode($res->getBody()->getContents());

   }

    public function salas()
    {
        $client = new Client();
        $res = $client->request('GET','http://localhost:8001/Web/Services/index.php/Resources/', [
            'headers' => [
                'X-Booked-SessionToken' => $this->session->sessionToken,
                'X-Booked-UserId' => $this->session->userId,
            ],
        ]);
        dd($res->getBody()->getContents());

    }

    public function reservas()
    {
        $client = new Client();
        $res = $client->request('GET','http://localhost:8001/Web/Services/index.php/Reservations/', [
            'headers' => [
                'X-Booked-SessionToken' => $this->session->sessionToken,
                'X-Booked-UserId' => $this->session->userId,
            ],
        ]);
        dd($res->getBody()->getContents());

    }
}
