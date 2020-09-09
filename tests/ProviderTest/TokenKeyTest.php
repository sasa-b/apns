<?php
/**
 * Created by PhpStorm.
 * User: sasa.blagojevic@mail.com
 * Date: 30/08/2020
 * Time: 17:20
 */

namespace SasaB\Tests\ProviderTest;


use Lcobucci\JWT\Token;
use SasaB\Apns\Provider\JWT;
use SasaB\Apns\Provider\TokenKey;

use PHPUnit\Framework\TestCase;
use SasaB\Tests\CreateTokenKeyTrust;

class TokenKeyTest extends TestCase
{
    use CreateTokenKeyTrust;

    private $teamId;

    public function testItCanCreateValidJWT()
    {
        $tokenKey = $this->makeTokenKey();

        $jwt = JWT::new($this->teamId, $tokenKey);

        $this->assertInstanceOf(Token::class,  $jwt->getToken());
        $this->assertRegExp('/^[a-zA-Z0-9\-_]+?\.[a-zA-Z0-9\-_]+?\.([a-zA-Z0-9\-_]+)?$/', (string) $jwt->getToken());
    }

    public function testItCanProvideAuthHeader()
    {
        $tokenKey = $this->makeTokenKey();

        $jwt = JWT::new($this->teamId, $tokenKey);

        $this->assertSame([
            'headers' => ['authorization' => 'Bearer '.$jwt->getToken()]
        ], $jwt->getAuthOptions());
    }

    public function testItCanCheckIfTokenExpired()
    {
        $tokenKey = $this->makeTokenKey();

        $jwt = JWT::new($this->teamId, $tokenKey);

        $this->assertFalse($jwt->hasExpired());
    }

}