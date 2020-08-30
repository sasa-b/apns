<?php
/**
 * Created by PhpStorm.
 * User: sasa.blagojevic@mail.com
 * Date: 30/08/2020
 * Time: 13:02
 */

namespace SasaB\Apns\Provider;

use Lcobucci\JWT\Builder;
use Lcobucci\JWT\Parser;
use Lcobucci\JWT\Signer\Key;
use Lcobucci\JWT\Signer\Ecdsa\Sha256;
use Lcobucci\JWT\Token;
use SasaB\Apns\Headers;


final class JWT implements Trust
{
    private $token;

    public function __construct(Token  $token)
    {
        $this->token = $token;
    }

    public function __toString()
    {
        return $this->getToken();
    }

    public static function new(string $teamId, TokenKey $tokenKey): JWT
    {
        $signer = new Sha256();
        $pk = new Key($tokenKey->getContent());

        $token = (new Builder())
            ->issuedBy($teamId) // (iss claim) // teamId
            ->issuedAt(time()) // time the token was issuedAt
            ->withHeader('kid', $tokenKey->getKeyId())
            ->getToken($signer,  $pk); // Retrieves the generated token

        return new self($token);
    }

    public static function parse(string $token): JWT
    {
        return new self(
            (new Parser())->parse($token)
        );
    }

    public function getToken(): string
    {
        return (string) $this->token;
    }

    public function getAuthOptions(): array
    {
        return [
            'headers' => [
                Headers::AUTHORIZATION => "Bearer: $this"
            ]
        ];
    }
}