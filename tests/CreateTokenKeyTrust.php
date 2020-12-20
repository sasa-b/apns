<?php
/**
 * Created by PhpStorm.
 * User: sasa.blagojevic@mail.com
 * Date: 09/09/2020
 * Time: 18:07
 */

namespace SasaB\Apns\Tests;


use SasaB\Apns\Provider\TokenKey;

trait CreateTokenKeyTrust
{
    protected $teamId;

    protected function makeTokenKey(): TokenKey
    {
        $testsDir = __DIR__.DIRECTORY_SEPARATOR;

        $keyId = file_get_contents($testsDir.'certs/key-id.txt');
        $this->teamId = file_get_contents($testsDir.'certs/team-id.txt');

        $tokenKey = new TokenKey($keyId);
        $tokenKey->loadFromFile($testsDir.'certs/AuthKey.p8');

        return $tokenKey;
    }
}