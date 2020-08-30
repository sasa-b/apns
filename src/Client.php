<?php

namespace SasaB\Apns;

use GuzzleHttp\ClientInterface;
use GuzzleHttp\Promise;

use Psr\Http\Message\ResponseInterface;

use SasaB\Apns\Provider\Certificate;
use SasaB\Apns\Provider\Trust;
use SasaB\Apns\Provider\JWT;


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

    public function send(Notification $notification): ResponseInterface
    {
        $promise = $this->http->requestAsync('POST', $notification->getDeviceToken(), [
            'json'    => $notification->getPayload(),
            'headers' => $notification->getHeaders()
        ]);
        return $promise->wait();
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
            $promises[] = $this->http->requestAsync('POST', $notification->getDeviceToken(), [
                'json'    => $notification->getPayload(),
                'headers' => $notification->getHeaders()
            ]);
        }
        Promise\settle($promises)->wait();
        return $promises;
    }

    public static function auth(Trust $trust, array $options = []): Client
    {
        $options = array_merge([
            'version'  => '2.0',
            'base_uri' => (string) ($options['base_uri'] ?? Uri::prod()),
            'headers'  => [
                'content-type' => 'application/json'
            ]
        ], $options);

        $options = array_merge_recursive($options, $trust->getAuthOptions());

        return new self($trust, new \GuzzleHttp\Client($options));
    }

    /**
     * @return Trust|Certificate|JWT
     */
    public function getTrust(): Trust
    {
        return $this->trust;
    }
}