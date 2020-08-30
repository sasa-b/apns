<?php
/**
 * Created by PhpStorm.
 * User: sasa.blagojevic@mail.com
 * Date: 28/08/2020
 * Time: 14:52
 */

namespace SasaB\Apns\Provider;


final class TokenKey
{
    /**
     * @var string
     */
    private $keyId;
    /**
     * @var string
     */
    private $file;
    /**
     * @var false|string
     */
    private $content;

    public function __construct(string $keyId, string $file = '')
    {
        $this->keyId = $keyId;
        $this->file = $file;

        if ($file) {
            $this->loadFromFile($file);
        }
    }

    public function __toString()
    {
        return $this->getContent();
    }

    public function loadFromFile(string $file)
    {
        if (!file_exists($file)) {
            throw new \InvalidArgumentException("File [$file] not found");
        }
        $this->file = $file;
        $this->content = file_get_contents($file);
    }

    public function getKeyId(): string
    {
        return $this->keyId;
    }

    public function getFile(): string
    {
        return $this->file;
    }

    public function getContent(): string
    {
        return (string) $this->content;
    }

    public static function fromFile(string $keyId, string $path): TokenKey
    {
        $token = new TokenKey($keyId);

        $token->loadFromFile($path);

        return $token;
    }
}