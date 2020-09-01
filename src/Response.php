<?php
/**
 * Created by PhpStorm.
 * User: sasa.blagojevic@mail.com
 * Date: 30/08/2020
 * Time: 21:03
 */

namespace SasaB\Apns;


use Psr\Http\Message\ResponseInterface;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;
use SasaB\Apns\Payload\CanBeCastToString;

final class Response implements \JsonSerializable
{
    const OK = 200;
    const BAD_REQUEST = 400;
    const AUTH_PROVIDER_ERROR = 403;
    const METHOD_NOT_ALLOWED = 405;
    const DEVICE_TOKEN_INACTIVE = 410;
    const PAYLOAD_TOO_LARGE = 413;
    const TOO_MANY_REQUESTS = 429;
    const SERVER_ERROR = 500;
    const SERVER_UNAVAILABLE = 503;

    use CanBeCastToString;

    private $apnsId;

    private $code;

    private $reason;

    private $description;

    private $timestamp;

    private static $errorDescriptions = [
        'BadCollapseId'               => 'The collapse identifier exceeds the maximum allowed size',
        'BadDeviceToken'              => 'The specified device token was bad. Verify that the request contains a valid token and that the token matches the environment',
        'BadExpirationDate'           => 'The apns-expiration value is bad',
        'BadMessageId'                => 'The apns-id value is bad',
        'BadPriority'                 => 'The apns-priority value is bad',
        'BadTopic'                    => 'The apns-topic was invalid',
        'DeviceTokenNotForTopic'      => 'The device token does not match the specified topic',
        'DuplicateHeaders'            => 'One or more headers were repeated',
        'IdleTimeout'                 => 'Idle time out',
        'MissingDeviceToken'          => 'The device token is not specified in the request :path. Verify that the :path header contains the device token',
        'MissingTopic'                => 'The apns-topic header of the request was not specified and was required. The apns-topic header is mandatory when the client is connected using a certificate that supports multiple topics',
        'PayloadEmpty'                => 'The message payload was empty',
        'TopicDisallowed'             => 'Pushing to this topic is not allowed',
        'BadCertificate'              => 'The certificate was bad',
        'BadCertificateEnvironment'   => 'The client certificate was for the wrong environment',
        'ExpiredProviderToken'        => 'The provider token is stale and a new token should be generated',
        'Forbidden'                   => 'The specified action is not allowed',
        'InvalidProviderToken'        => 'The provider token is not valid or the token signature could not be verified',
        'MissingProviderToken'        => 'No provider certificate was used to connect to APNs and Authorization header was missing or no provider token was specified',
        'BadPath'                     => 'The request contained a bad :path value',
        'MethodNotAllowed'            => 'The specified :method was not POST',
        'Unregistered'                => 'The device token is inactive for the specified topic',
        'PayloadTooLarge'             => 'The message payload was too large',
        'TooManyProviderTokenUpdates' => 'The provider token is being updated too often',
        'TooManyRequests'             => 'Too many requests were made consecutively to the same device token',
        'InternalServerError'         => 'An internal server error occurred',
        'ServiceUnavailable'          => 'The service is unavailable',
        'Shutdown'                    => 'The server is shutting down',
    ];

    public function jsonSerialize()
    {
        return $this->code === 200
            ? ['code' => $this->code, 'reason' => $this->reason]
            : ['code' => $this->code, 'reason' => $this->reason, 'description' => $this->description];
    }

    public static function fromPsr7(ResponseInterface $psr): Response
    {
        $response = new self();
        $response->code = $psr->getStatusCode();
        $response->apnsId = $psr->getHeader('apns-id')[0];

        if ($response->code === self::OK) {
            $response->reason = $psr->getReasonPhrase();
            return $response;
        }

        $body = json_decode($psr->getBody()->getContents(), true);

        $response->reason = $body['reason'];
        $response->description = self::$errorDescriptions[$body['reason']];

        if ($response->code === self::DEVICE_TOKEN_INACTIVE) {
            $response->timestamp = $body['timestamp'];
        }

        return $response;
    }

    public function getApnsId(): UuidInterface
    {
        return Uuid::fromString($this->apnsId);
    }

    public function getCode(): int
    {
        return $this->code;
    }

    public function getReason(): string
    {
        return $this->reason;
    }

    public function getDescription(): string
    {
        return $this->description ?? '';
    }

    public function getTimestamp(): string
    {
        return $this->timestamp ?? '';
    }
}