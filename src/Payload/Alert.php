<?php
/**
 * Created by PhpStorm.
 * User: sasa.blagojevic@mail.com
 * Date: 27/08/2020
 * Time: 14:38
 */

namespace SasaB\Payload;

final class Alert implements \JsonSerializable
{
    /**
     * @var string
     */
    private $text;
    /**
     * @var string|null
     */
    private $title;
    /**
     * @var string|null
     */
    private $titleLocKey;
    /**
     * @var array
     */
    private $titleLocArgs;
    /**
     * @var string|null
     */
    private $actionLocKey;
    /**
     * @var string|null
     */
    private $locKey;
    /**
     * @var array
     */
    private $locArgs;
    /**
     * @var string|null
     */
    private $launchImage;

    public function __construct(
        string $text,
        string $title = null,
        string $titleLocKey = null,
        array $titleLocArgs = [],
        string $actionLocKey = null,
        string $locKey = null,
        array $locArgs = [],
        string $launchImage = null)
    {

        $this->text = $text;
        $this->title = $title;
        $this->titleLocKey = $titleLocKey;
        $this->titleLocArgs = $titleLocArgs;
        $this->actionLocKey = $actionLocKey;
        $this->locKey = $locKey;
        $this->locArgs = $locArgs;
        $this->launchImage = $launchImage;
    }

    public function __toString()
    {
        $encoded = json_encode($this);

        return $encoded === false ? json_last_error_msg() : $encoded;
    }

    public function jsonSerialize()
    {
        $members = get_object_vars($this);
        foreach ($members as $key => $value) {
            if ($value === null || $value === []) {
                unset($members[$key]);
            }
        }
        return $members;
    }

    /**
     * @param string $text
     * @return Alert
     */
    public function setText(string $text): Alert
    {
        $this->text = $text;
        return $this;
    }

    /**
     * @param string|null $title
     * @return Alert
     */
    public function setTitle(string $title): Alert
    {
        $this->title = $title;
        return $this;
    }

    /**
     * @param string|null $titleLocKey
     * @return Alert
     */
    public function setTitleLocKey(string $titleLocKey): Alert
    {
        $this->titleLocKey = $titleLocKey;
        return $this;
    }

    /**
     * @param array $titleLocArgs
     * @return Alert
     */
    public function setTitleLocArgs(array $titleLocArgs): Alert
    {
        $this->titleLocArgs = $titleLocArgs;
        return $this;
    }

    /**
     * @param string|null $actionLocKey
     * @return Alert
     */
    public function setActionLocKey(string $actionLocKey): Alert
    {
        $this->actionLocKey = $actionLocKey;
        return $this;
    }

    /**
     * @param string|null $locKey
     * @return Alert
     */
    public function setLocKey(string $locKey): Alert
    {
        $this->locKey = $locKey;
        return $this;
    }

    /**
     * @param array $locArgs
     * @return Alert
     */
    public function setLocArgs(array $locArgs): Alert
    {
        $this->locArgs = $locArgs;
        return $this;
    }

    /**
     * @param string|null $launchImage
     * @return Alert
     */
    public function setLaunchImage(string $launchImage): Alert
    {
        $this->launchImage = $launchImage;
        return $this;
    }

    /**
     * @return string
     */
    public function getText(): string
    {
        return $this->text;
    }

    /**
     * @return string|null
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * @return string|null
     */
    public function getTitleLocKey(): string
    {
        return $this->titleLocKey;
    }

    /**
     * @return array
     */
    public function getTitleLocArgs(): array
    {
        return $this->titleLocArgs;
    }

    /**
     * @return string|null
     */
    public function getActionLocKey(): string
    {
        return $this->actionLocKey;
    }

    /**
     * @return string|null
     */
    public function getLocKey(): string
    {
        return $this->locKey;
    }

    /**
     * @return array
     */
    public function getLocArgs(): array
    {
        return $this->locArgs;
    }

    /**
     * @return string|null
     */
    public function getLaunchImage(): string
    {
        return $this->launchImage;
    }
}