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
    private string $content;

    public function __construct(
        private string $keyId,
        private string $file = ''
    )
    {
        if ($file) {
            $this->loadFromFile($file);
        }
    }

    public static function fromFile(string $keyId, string $path): TokenKey
    {
        $token = new TokenKey($keyId);

        $token->loadFromFile($path);

        return $token;
    }

    public function loadFromFile(string $file): void
    {
        if (!file_exists($file)) {
            throw new \InvalidArgumentException("File [$file] not found");
        }
        $this->file = $file;
        $this->content = file_get_contents($file) ?: '';
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
        return $this->content;
    }

    public function __toString(): string
    {
        return $this->getContent();
    }
}