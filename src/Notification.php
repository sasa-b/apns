<?php

namespace SasaB\Apns;


use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;
use SasaB\Apns\Payload\Aps;
use SasaB\Apns\Payload\CanBeCastToString;

/**
 * Created by PhpStorm.
 * User: sasa.blagojevic@mail.com
 * Date: 27/08/2020
 * Time: 14:25
 */

final class Notification implements \JsonSerializable
{
    use CanBeCastToString;

    public const HIGH_PRIORITY = 10;
    public const LOW_PRIORITY = 5;

    private UuidInterface $apsId;

    public function __construct(
        private string $deviceToken,
        private ?Aps $aps = null,
        private ?string $pushTopic = null,
        private array $custom = [],
        private ?int $expiresAt = null,
        private ?string $collapseId = null,
        private ?int $priority = null,
        private ?string $pushType = null
    )
    {
        $this->apsId = Uuid::uuid4();
    }

    public function getPayload(): array
    {
        if ($this->aps) {
            return array_merge(['aps' => $this->aps->asArray()], $this->custom);
        }
        return $this->custom;
    }

    public function jsonSerialize()
    {
        return $this->getPayload();
    }

    public function setApsId(UuidInterface $apsId): Notification
    {
        $this->apsId = $apsId;
        return $this;
    }

    public function setDeviceToken(string $token): Notification
    {
        $this->deviceToken = $token;
        return $this;
    }

    public function setPushTopic(string $pushTopic): Notification
    {
        $this->pushTopic = $pushTopic;
        return $this;
    }

    public function setAps(Aps $aps): Notification
    {
        $this->aps = $aps;
        return $this;
    }

    public function setCustom(array $custom): Notification
    {
        $this->custom = $custom;
        return $this;
    }

    public function setCustomKey(string $key, $value): Notification
    {
        $this->custom[$key] = $value;
        return $this;
    }

    public function setExpiresAt(int $expiresAt): Notification
    {
        $this->expiresAt = $expiresAt;
        return $this;
    }

    public function setCollapseId(string $collapseId): Notification
    {
        $this->collapseId = $collapseId;
        return $this;
    }

    public function setHighPriority(): Notification
    {
        $this->priority = self::HIGH_PRIORITY;
        return $this;
    }

    public function setLowPriority(): Notification
    {
        $this->priority = self::LOW_PRIORITY;
        return $this;
    }

    public function setPushType(string $pushType): Notification
    {
        $this->pushType = $pushType;
        return $this;
    }

    public function getApsId(): UuidInterface
    {
        return $this->apsId;
    }

    public function getDeviceToken(): string
    {
        return $this->deviceToken;
    }

    public function getPushTopic(): ?string
    {
        return $this->pushTopic;
    }

    public function getAps(): ?Aps
    {
        return $this->aps;
    }

    public function getCustom(): array
    {
        return $this->custom;
    }

    public function getExpiresAt(): ?int
    {
        return $this->expiresAt;
    }

    public function getPriority(): ?int
    {
        return $this->priority;
    }

    public function getPushType(): ?string
    {
        return $this->pushType;
    }

    public function getHeaders(): array
    {
        $headers = [
            Header::APNS_ID          => (string) $this->apsId,
            Header::APNS_EXPIRATION  => $this->expiresAt,
            Header::APNS_TOPIC       => $this->pushTopic,
            Header::APNS_COLLAPSE_ID => $this->collapseId,
            Header::APNS_PRIORITY    => $this->priority,
            Header::APNS_PUSH_TYPE   => $this->pushType
        ];

        foreach ($headers as $k => $v) {
            if (!$v) {
                unset($headers[$k]);
            }
        }

        return $headers;
    }
}