<?php

namespace SasaB\Payload;


/**
 * Created by PhpStorm.
 * User: sasa.blagojevic@mail.com
 * Date: 27/08/2020
 * Time: 15:12
 */

use SasaB\Payload\Alert;

final class Aps implements \JsonSerializable
{
    /**
     * @var Alert
     */
    private $alert;
    /**
     * @var int
     */
    private $badge;
    /**
     * @var string|null
     */
    private $sound;
    /**
     * @var int|null
     */
    private $contentAvailable;
    /**
     * @var string|null
     */
    private $category;
    /**
     * @var string|null
     */
    private $threadId;

    public function __construct(
       Alert $alert,
       int $badge = 10,
       string $sound = null,
       int $contentAvailable = null,
       string $category = null,
       string $threadId = null)
   {
       $this->alert = $alert;
       $this->badge = $badge;
       $this->sound = $sound;
       $this->contentAvailable = $contentAvailable;
       $this->category = $category;
       $this->threadId = $threadId;
   }

   public function __toString()
   {
       $encoded = json_encode(array_merge(['aps' => $aps], $this->custom));

       return $encoded === false ? json_last_error_msg() : $encoded;
   }

   public function jsonSerialize()
   {
        $members = get_object_vars($this);
        foreach ($members as $key => $value) {
            if ($value === null) {
                unset($members[$key]);
            }
        }
        return $members;
   }

    /**
     * @param mixed $alert
     * @return Aps
     */
    public function setAlert(Alert $alert): Aps
    {
        $this->alert = $alert;
        return $this;
    }

    /**
     * @param mixed $badge
     * @return Aps
     */
    public function setBadge(string $badge): Aps
    {
        $this->badge = $badge;
        return $this;
    }

    /**
     * @param mixed $sound
     * @return Aps
     */
    public function setSound(string $sound): Aps
    {
        $this->sound = $sound;
        return $this;
    }

    /**
     * @param mixed $contentAvailable
     * @return Aps
     */
    public function setContentAvailable(int $contentAvailable): Aps
    {
        $this->contentAvailable = $contentAvailable;
        return $this;
    }

    /**
     * @param mixed $category
     * @return Aps
     */
    public function setCategory($category): Aps
    {
        $this->category = $category;
        return $this;
    }

    /**
     * @param string|null $threadId
     * @return Aps
     */
    public function setThreadId(string $threadId): Aps
    {
        $this->threadId = $threadId;
        return $this;
    }

    /**
     * @return \SasaB\Payload\Alert
     */
    public function getAlert(): Alert
    {
        return $this->alert;
    }

    /**
     * @return int
     */
    public function getBadge(): int
    {
        return $this->badge;
    }

    /**
     * @return string|null
     */
    public function getSound()
    {
        return $this->sound;
    }

    /**
     * @return int|null
     */
    public function getContentAvailable()
    {
        return $this->contentAvailable;
    }

    /**
     * @return string|null
     */
    public function getCategory()
    {
        return $this->category;
    }

    /**
     * @return string|null
     */
    public function getThreadId()
    {
        return $this->threadId;
    }
}