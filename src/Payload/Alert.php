<?php
/**
 * Created by PhpStorm.
 * User: sasa.blagojevic@mail.com
 * Date: 27/08/2020
 * Time: 14:38
 */

namespace Sco\Apns\Payload;

final class Alert implements \JsonSerializable
{
    use CanBeCastToString;

    public function __construct(
        private string $text,
        private ?string $title = null,
        private ?string $subtitle = null,
        private ?string $titleLocKey = null,
        private array $titleLocArgs = [],
        private ?string $actionLocKey = null,
        private ?string $locKey = null,
        private array $locArgs = [],
        private ?string $launchImage = null
    ) {}

    public function asArray(): array
    {
        $alert = [
            AlertKey::TITLE          => $this->title,
            AlertKey::SUBTITLE       => $this->subtitle,
            AlertKey::BODY           => $this->text,
            AlertKey::TITLE_LOC_KEY  => $this->titleLocKey,
            AlertKey::TITLE_LOC_ARGS => $this->titleLocArgs,
            AlertKey::ACTION_LOC_KEY => $this->actionLocKey,
            AlertKey::LOC_KEY        => $this->locKey,
            AlertKey::LOC_ARGS       => $this->locArgs,
            AlertKey::LAUNCH_IMAGE   => $this->launchImage,
        ];
        foreach ($alert as $key => $value) {
            if ($value === null || $value === []) {
                unset($alert[$key]);
            }
        }
        return $alert;
    }

    public function jsonSerialize(): array
    {
        return $this->asArray();
    }

    public function setText(string $text): Alert
    {
        $this->text = $text;
        return $this;
    }

    public function setTitle(string $title): Alert
    {
        $this->title = $title;
        return $this;
    }

    public function setSubtitle(string $subtitle): Alert
    {
        $this->subtitle = $subtitle;
        return $this;
    }

    public function setTitleLocKey(string $titleLocKey): Alert
    {
        $this->titleLocKey = $titleLocKey;
        return $this;
    }

    public function setTitleLocArgs(array $titleLocArgs): Alert
    {
        $this->titleLocArgs = $titleLocArgs;
        return $this;
    }

    public function setActionLocKey(string $actionLocKey): Alert
    {
        $this->actionLocKey = $actionLocKey;
        return $this;
    }

    public function setLocKey(string $locKey): Alert
    {
        $this->locKey = $locKey;
        return $this;
    }

    public function setLocArgs(array $locArgs): Alert
    {
        $this->locArgs = $locArgs;
        return $this;
    }

    public function setLaunchImage(string $launchImage): Alert
    {
        $this->launchImage = $launchImage;
        return $this;
    }

    public function getText(): string
    {
        return $this->text;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function getTitleLocKey(): ?string
    {
        return $this->titleLocKey;
    }

    public function getTitleLocArgs(): array
    {
        return $this->titleLocArgs;
    }

    public function getActionLocKey(): ?string
    {
        return $this->actionLocKey;
    }

    public function getLocKey(): ?string
    {
        return $this->locKey;
    }

    public function getLocArgs(): array
    {
        return $this->locArgs;
    }

    public function getLaunchImage(): ?string
    {
        return $this->launchImage;
    }
}
