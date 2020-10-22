<?php

namespace App\Booked;

use GuzzleHttp\Client;


class Booked
{
    private $session;

    public function __construct()
    {
        $client = new Client();
        $res = $client->request('POST', config('salas.bookedHost') . '/Authentication/Authenticate', [
            'json' => [
                'username' => config('salas.bookedUser'),
                'password' => config('salas.bookedPass'),
            ],
        ]);

        $this->session = json_decode($res->getBody()->getContents());

   }

    public function salas()
    {
        $client = new Client();
        $res = $client->request('GET', config('salas.bookedHost') . '/Resources/', [
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
        $res = $client->request('GET', config('salas.bookedHost') . '/Reservations/', [
            'headers' => [
                'X-Booked-SessionToken' => $this->session->sessionToken,
                'X-Booked-UserId' => $this->session->userId,
            ],
        ]);
        dd($res->getBody()->getContents());

    }
}
