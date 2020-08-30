<?php
/**
 * Created by PhpStorm.
 * User: sasa.blagojevic@mail.com
 * Date: 30/08/2020
 * Time: 12:31
 */

namespace SasaB\Apns;


final class Uri
{
    const DEV = 'https://api.development.push.apple.com';
    const PROD = 'https://api.push.apple.com';
    const PORT = 443;
    const PATH = '/3/device/';

    private $value;

    private function __construct() {}

    public static function dev(): Uri
    {
        $uri = new self();
        $uri->value = self::DEV.self::PATH;
        return $uri;
    }

    public static function prod(): Uri
    {
        $uri = new self();
        $uri->value = self::PROD.self::PATH;
        return $uri;
    }

    public function asString(): string
    {
        return $this->value;
    }

    public function __toString()
    {
        return $this->value;
    }
}