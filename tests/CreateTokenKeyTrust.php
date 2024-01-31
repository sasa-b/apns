<?php
/**
 * Created by PhpStorm.
 * User: sasa.blagojevic@mail.com
 * Date: 09/09/2020
 * Time: 18:07
 */

namespace Sco\Apns\Tests;


use Sco\Apns\Provider\Token\Key;

trait CreateTokenKeyTrust
{
    protected string $teamId = '';

    protected function makeTokenKey(string $filename = null): Key
    {
        $testsDir = __DIR__.DIRECTORY_SEPARATOR;

        $keyId = file_get_contents($testsDir.'certs/key-id.txt');
        $this->teamId = file_get_contents($testsDir.'certs/team-id.txt');

        $filename ??= 'AuthKey.p8';

        return Key::loadFromFile("{$testsDir}certs/$filename", $keyId);
    }
}
