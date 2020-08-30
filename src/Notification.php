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

    private $apsId;

    private $deviceToken;

    private $aps;

    private $custom = [];

    public function __construct(string $deviceToken, Aps $aps = null)
    {
        $this->apsId = Uuid::uuid4();
        $this->deviceToken = $deviceToken;
        $this->aps = $aps;
    }

    public function asArray(): array
    {
        if ($this->aps) {
            return array_merge(['aps' => $this->aps->asArray()], $this->custom);
        }
        return $this->custom;
    }

    public function jsonSerialize()
    {
        return $this->asArray();
    }

    public function setAps(Aps $aps): Notification
    {
        $this->aps = $aps;
        return $this;
    }

    public function setDeviceToken(string $token): Notification
    {
        $this->deviceToken = $token;
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

    public function setApsId(UuidInterface $apsId): Notification
    {
        $this->apsId = $apsId;
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
}