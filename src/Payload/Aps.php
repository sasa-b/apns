<?php

namespace SasaB\Apns\Payload;


/**
 * Created by PhpStorm.
 * User: sasa.blagojevic@mail.com
 * Date: 27/08/2020
 * Time: 15:12
 */

final class Aps implements \JsonSerializable
{
    use CanBeCastToString;

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
       int $badge = null,
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

    public function asArray(): array
    {
        $alert = $this->alert->asArray();

        if (count($alert) === 1) {
            $alert = $this->alert->getText();
        }

        $aps = [
            ApsKey::ALERT             => $alert,
            ApsKey::BADGE             => $this->badge,
            ApsKey::SOUND             => $this->sound,
            ApsKey::CONTENT_AVAILABLE => $this->contentAvailable,
            ApsKey::CATEGORY          => $this->category,
            ApsKey::THREAD_ID         => $this->threadId
        ];

        foreach ($aps as $key => $value) {
            if ($value === null) {
                unset($aps[$key]);
                continue;
            }
        }

        return $aps;
    }

   public function jsonSerialize()
   {
       return $this->asArray();
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
    public function setCategory(string $category): Aps
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

    public function getAlert(): Alert
    {
        return $this->alert;
    }

    /**
     * @return int|null
     */
    public function getBadge()
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