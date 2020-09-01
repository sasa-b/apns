<?php
/**
 * Created by PhpStorm.
 * User: sasa.blagojevic@mail.com
 * Date: 27/08/2020
 * Time: 14:39
 */

namespace SasaB\Apns\Provider;


use GuzzleHttp\RequestOptions;
use SasaB\Apns\Header;

final class Certificate implements Trust
{
    private $file;

    private $pushTopic;

    public function __construct(string $file)
    {
        $this->file = $file;

        if (extension_loaded('openssl')) {
            $cert = openssl_x509_parse(file_get_contents($file));
            $this->pushTopic = $cert['subject']['UID'];
        }
    }

    public static function fromFile(string $path): Certificate
    {
        if (!file_exists($path)) throw new \InvalidArgumentException("File [$path] not found");

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
}