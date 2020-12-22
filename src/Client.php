<?php

namespace SasaB\Apns;

use GuzzleHttp\ClientInterface;
use GuzzleHttp\Exception\BadResponseException;
use GuzzleHttp\Promise;

use SasaB\Apns\Provider\Trust;

/**
 * Created by PhpStorm.
 * User: sasa.blagojevic@mail.com
 * Date: 27/08/2020
 * Time: 12:57
 */

final class Client
{
    private Trust $trust;

    private ClientInterface $http;

    private function __construct(Trust $trust, ClientInterface $http)
    {
        $this->trust = $trust;
        $this->http = $http;
    }

    public function send(Notification $notification): Response
    {
        $promise = $this->http->requestAsync('POST', $notification->getDeviceToken(), [
            'json'    => $notification->getPayload(),
            'headers' => $notification->getHeaders()
        ]);

        try {
            $response = Response::fromPsr7($promise->wait());
        } catch (BadResponseException $e) {
            $response = Response::fromPsr7($e->getResponse());
        }
        return $response;
    }

    /**
     * @param \SasaB\Apns\Notification[] $notifications
     * @return \SasaB\Apns\Response[]
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
        $responses = [];
        foreach (Promise\settle($promises)->wait() as $settled) {
            if (isset($settled['reason'])) {
                $responses[] = Response::fromPsr7($settled['reason']->getResponse());
            } else {
                $responses[] = Response::fromPsr7($settled['value']);
            }
        }
        return $responses;
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

    public function getTrust(): Trust
    {
        return $this->trust;
    }
}