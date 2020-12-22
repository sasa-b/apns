<?php
/**
 * Created by PhpStorm.
 * User: sasa.blagojevic@mail.com
 * Date: 30/08/2020
 * Time: 17:20
 */

namespace SasaB\Apns\Tests\ProviderTest;


use SasaB\Apns\Provider\Token\JWT;
use SasaB\Apns\Tests\CreateTokenKeyTrust;

use PHPUnit\Framework\TestCase;


class TokenKeyTest extends TestCase
{
    use CreateTokenKeyTrust;

    public function testItCanCreateValidJWT()
    {
        $tokenKey = $this->makeTokenKey();

        $jwt = JWT::with($this->teamId, $tokenKey);

        self::assertRegExp('/^[a-zA-Z0-9\-_]+?\.[a-zA-Z0-9\-_]+?\.([a-zA-Z0-9\-_]+)?$/', $jwt->asString());
    }

    public function testItCanProvideAuthHeader()
    {
        $tokenKey = $this->makeTokenKey();

        $jwt = JWT::with($this->teamId, $tokenKey);

        self::assertSame([
            'headers' => ['authorization' => 'Bearer '.$jwt->asString()]
        ], $jwt->getAuthOptions());
    }

    public function testItCanCheckIfTokenExpired()
    {
        $tokenKey = $this->makeTokenKey();

        $jwt = JWT::with($this->teamId, $tokenKey);

        self::assertFalse($jwt->hasExpired());
    }

    public function testItCanReadKeyIdFromAuthKeyFileName()
    {
        $keyId = file_get_contents(dirname(__DIR__).'/certs/key-id.txt');

        $tokenKey = $this->makeTokenKey("AuthKey_{$keyId}.p8");

        self::assertSame($keyId, $tokenKey->getKeyId());
    }
}