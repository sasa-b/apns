<?php

namespace SasaB\Apns;

use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;
use SasaB\Payload\Aps;

/**
 * Created by PhpStorm.
 * User: sasa.blagojevic@mail.com
 * Date: 27/08/2020
 * Time: 14:25
 */

final class Notification implements \JsonSerializable
{
    private $apsId;

    private $token;

    private $aps;

    private $custom = [];

    public function __construct(string $token, Aps $aps = null)
    {
        $this->apsId = Uuid::uuid4();
        $this->token = $token;
        $this->aps = $aps;
    }

    public function __toString()
    {
        $encoded = json_encode($this);

        return $encoded === false ? json_last_error_msg() : $encoded;
    }

    public function jsonSerialize()
    {
        return array_merge($this->aps->jsonSerialize(), $this->custom);
    }

    public function setAps(Aps $aps): Notification
    {
        $this->aps = $aps;
        return $this;
    }

    public function setDevice(string $token): Notification
    {
        $this->token = $token;
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
     * @return \Ramsey\Uuid\UuidInterface
     */
    public function getApsId(): UuidInterface
    {
        return $this->apsId;
    }

    /**
     * @return string
     */
    public function getToken(): string
    {
        return $this->token;
    }

    /**
     * @return \SasaB\Payload\Aps|null
     */
    public function getAps()
    {
        return $this->aps;
    }

    /**
     * @return array
     */
    public function getCustom(): array
    {
        return $this->custom;
    }
}