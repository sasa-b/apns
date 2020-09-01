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

    const HIGH_PRIORITY = 10;

    const LOW_PRIORITY = 5;

    private $aps;

    private $apsId;

    private $deviceToken;

    private $pushTopic;

    private $custom = [];

    private $expiresAt;

    private $collapseId;

    private $priority;

    private $pushType;

    public function __construct(string $deviceToken, Aps $aps = null)
    {
        $this->apsId = Uuid::uuid4();
        $this->deviceToken = $deviceToken;
        $this->aps = $aps;
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

    public function setApsId(UuidInterface $apsId = null): Notification
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

    /**
     * @param int $expiresAt
     * @return Notification
     */
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

    /**
     * @return UuidInterface|null
     */
    public function getApsId()
    {
        return $this->apsId;
    }

    public function getDeviceToken(): string
    {
        return $this->deviceToken;
    }

    /**
     * @return string|null
     */
    public function getPushTopic()
    {
        return $this->pushTopic;
    }

    /**
     * @return Aps|null
     */
    public function getAps()
    {
        return $this->aps;
    }

    public function getCustom(): array
    {
        return $this->custom;
    }

    /**
     * @return int|null
     */
    public function getExpiresAt()
    {
        return $this->expiresAt;
    }

    /**
     * @return int|null
     */
    public function getPriority()
    {
        return $this->priority;
    }

    /**
     * @return string|null
     */
    public function getPushType()
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
            if (!$v) unset($headers[$k]);
        }

        return $headers;
    }
}