<?php

namespace SasaB\Apns;

use GuzzleHttp\ClientInterface;
use GuzzleHttp\Promise;
use Psr\Http\Message\ResponseInterface;
use SasaB\Provider\Trust;

/**
 * Created by PhpStorm.
 * User: sasa.blagojevic@mail.com
 * Date: 27/08/2020
 * Time: 12:57
 */

final class Client
{
    private $trust;

    private $http;

    private function __construct(Trust $trust, ClientInterface $http)
    {
        $this->trust = $trust;
        $this->http = $http;
    }

    public function dev(): Client
    {
    }

    public function send(Notification $notification): ResponseInterface
    {
        return $this->http->post($notification->getToken(), [

        ]);
    }

    /**
     * @param \SasaB\Apns\Notification[] $notifications
     * @return array
     */
    public function sendBatch(array $notifications): array
    {
        /** @var \GuzzleHttp\Promise\PromiseInterface[] $promises */
        $promises = [];
        foreach ($notifications as $notification) {
            $promises[] = $this->http->postAsync($notification->getToken(), [

            ]);
        }
        Promise\settle($promises)->wait();
        return $promises;
    }

    public static function auth(Trust $trust, array $options = []): Client
    {
        $http = new \GuzzleHttp\Client([
            'base_uri' => (string) ($options['base_uri'] ?? Uri::prod())
        ]);

        return new self($trust, $http);
    }
}