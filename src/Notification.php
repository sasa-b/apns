<?php

namespace SasaB\Apns;

use SasaB\Payload\Aps;

/**
 * Created by PhpStorm.
 * User: sasa.blagojevic@mail.com
 * Date: 27/08/2020
 * Time: 14:25
 */

final class Notification implements \JsonSerializable
{
    private $aps;

    private $deviceToken;

    private $custom = [];

    public function __construct(string $deviceToken, Aps $aps)
    {
        $this->deviceToken = $deviceToken;
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

    /**
     * @param string $deviceToken
     * @return Notification
     */
    public function setDeviceToken(string $deviceToken): Notification
    {
        $this->deviceToken = $deviceToken;
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
}