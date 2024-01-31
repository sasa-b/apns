<?php
/**
 * Created by PhpStorm.
 * User: sasa.blagojevic@mail.com
 * Date: 27/08/2020
 * Time: 14:39
 */

namespace Sco\Apns\Provider;


use GuzzleHttp\RequestOptions;
use Sco\Apns\Header;

final class Certificate implements Trust
{
    private string $file;

    private ?string $pushTopic = null;

    public function __construct(string $file)
    {
        $this->file = $file;
        $cert = openssl_x509_parse(file_get_contents($file));
        $this->pushTopic = $cert['subject']['UID'];
    }

    public static function fromFile(string $path): Certificate
    {
        if (!file_exists($path)) {
            throw new \InvalidArgumentException("File [$path] not found");
        }

        return new self($path);
    }

    public function getAuthOptions(): array
    {
        $options = [
            RequestOptions::CERT => $this->file
        ];

        if ($this->pushTopic) {
             $options['headers'] = [
                 Header::APNS_TOPIC => $this->pushTopic
             ];
        }

        return $options;
    }

    public function getFilePath(): string
    {
        return $this->file;
    }
}
