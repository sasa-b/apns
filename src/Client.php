<?php

namespace SasaB\Apns;

use Psr\Http\Message\StreamInterface;
use SasaB\Provider\Trust;

/**
 * Created by PhpStorm.
 * User: sasa.blagojevic@mail.com
 * Date: 27/08/2020
 * Time: 12:57
 */

final class Client
{
    private $stream;

    private function __construct(StreamInterface $stream)
    {
        $this->stream = $stream;
    }

    public function send(Notification $notification)
    {
        $this->stream->write($notification->getPayload());
    }

    private static function httpClient()
    {

    }

    public static function auth(Trust $trust, array $options = []): Client
    {
        $http = new \GuzzleHttp\Client([

        ]);

        $stream = $http->request('POST', '/', []);

        $client = new Client();

        return $client;
    }
}