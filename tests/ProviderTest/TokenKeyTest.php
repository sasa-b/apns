<?php
/**
 * Created by PhpStorm.
 * User: sasa.blagojevic@mail.com
 * Date: 30/08/2020
 * Time: 17:20
 */

namespace SasaB\Apns\Tests\ProviderTest;


use Lcobucci\JWT\Token;

use SasaB\Apns\Provider\JWT;
use SasaB\Apns\Tests\CreateTokenKeyTrust;

use PHPUnit\Framework\TestCase;


class TokenKeyTest extends TestCase
{
    use CreateTokenKeyTrust;

    protected $teamId;

    public function testItCanCreateValidJWT()
    {
        $tokenKey = $this->makeTokenKey();

        $jwt = JWT::new($this->teamId, $tokenKey);

        self::assertInstanceOf(Token::class,  $jwt->getToken());
        self::assertRegExp('/^[a-zA-Z0-9\-_]+?\.[a-zA-Z0-9\-_]+?\.([a-zA-Z0-9\-_]+)?$/', (string) $jwt->getToken());
    }

    public function testItCanProvideAuthHeader()
    {
        $tokenKey = $this->makeTokenKey();

        $jwt = JWT::new($this->teamId, $tokenKey);

        self::assertSame([
            'headers' => ['authorization' => 'Bearer '.$jwt->getToken()]
        ], $jwt->getAuthOptions());
    }

    public function testItCanCheckIfTokenExpired()
    {
        $tokenKey = $this->makeTokenKey();

        $jwt = JWT::new($this->teamId, $tokenKey);

        self::assertFalse($jwt->hasExpired());
    }

}