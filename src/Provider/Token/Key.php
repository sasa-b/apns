<?php
/**
 * Created by PhpStorm.
 * User: sasa.blagojevic@mail.com
 * Date: 28/08/2020
 * Time: 14:52
 */

namespace SasaB\Apns\Provider\Token;


final class Key
{
    private ?string $file = null;
    private ?\OpenSSLAsymmetricKey $resource = null;

    public function __construct(
        private string $keyId,
        private string $content
    )
    {
        if ($content) {
            $this->openSSLPrivateKey($content);
        }
    }

    public function __toString(): string
    {
        return $this->getContents();
    }

    public static function loadFromFile(string $path, string $keyId = null): Key
    {
        if (!file_exists($path)) {
            throw new \InvalidArgumentException("File [$path] not found");
        }
        $key = new Key($keyId ?? '', '');
        if (!$keyId) {
            $key->keyId = $key->getKeyIdFromFileName() ?? '';
            if (!$key->keyId) {
                throw new Exception("Missing key id");
            }
        }
        $key->file = $path;
        $key->content = $key->readFile();
        $key->resource = $key->openSSLPrivateKey($key->content);
        return $key;
    }

    private function openSSLPrivateKey(string $content): \OpenSSLAsymmetricKey
    {
        $resource = openssl_pkey_get_private($content);
        if (!$resource) {
            throw new Exception(openssl_error_string());
        }
        return $resource;
    }

    private function readFile(): string
    {
        $content = file_get_contents($this->file);

        if (!$content) {
            throw new Exception("Private key file contents is empty");
        }

        return $content;
    }

    private function getKeyIdFromFileName(): ?string
    {
        $file = new \SplFileInfo($this->file);
        $filename = $file->getFilename();
        $ext = $file->getExtension();

        if (str_contains($filename, '_')) {
            return explode('_', str_replace(".$ext", '', $filename))[1] ?? null;
        }

        return null;
    }

    public function getKeyId(): string
    {
        return $this->keyId;
    }

    public function getFilePath(): ?string
    {
        return $this->file;
    }

    public function getContents(): string
    {
        return $this->content;
    }

    public function getOpenSSLPrivateKey(): ?\OpenSSLAsymmetricKey
    {
        return $this->resource ??= $this->openSSLPrivateKey($this->content);
    }
}