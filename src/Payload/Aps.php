<?php

namespace Sco\Apns\Payload;


/**
 * Created by PhpStorm.
 * User: sasa.blagojevic@mail.com
 * Date: 27/08/2020
 * Time: 15:12
 */

final class Aps implements \JsonSerializable
{
    use CanBeCastToString;

    public function __construct(
       private Alert $alert,
       private ?int $badge = null,
       private ?string $sound = null,
       private ?int $contentAvailable = null,
       private ?string $category = null,
       private ?string $threadId = null
    ) {}

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

   public function jsonSerialize(): array
   {
       return $this->asArray();
   }

    public function setAlert(Alert $alert): Aps
    {
        $this->alert = $alert;
        return $this;
    }

    public function setBadge(int $badge): Aps
    {
        $this->badge = $badge;
        return $this;
    }

    public function setSound(string $sound): Aps
    {
        $this->sound = $sound;
        return $this;
    }

    public function setContentAvailable(int $contentAvailable): Aps
    {
        $this->contentAvailable = $contentAvailable;
        return $this;
    }

    public function setCategory(string $category): Aps
    {
        $this->category = $category;
        return $this;
    }

    public function setThreadId(string $threadId): Aps
    {
        $this->threadId = $threadId;
        return $this;
    }

    public function getAlert(): Alert
    {
        return $this->alert;
    }

    public function getBadge(): ?int
    {
        return $this->badge;
    }

    public function getSound(): ?string
    {
        return $this->sound;
    }

    public function getContentAvailable(): ?int
    {
        return $this->contentAvailable;
    }

    public function getCategory(): ?string
    {
        return $this->category;
    }

    public function getThreadId(): ?string
    {
        return $this->threadId;
    }
}
