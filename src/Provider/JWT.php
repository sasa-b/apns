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
use SasaB\Apns\Header;


final class JWT implements Trust
{
    private $token;

    public function __construct(Token  $token)
    {
        $this->token = $token;
    }

    public function __toString()
    {
        return $this->asString();
    }

    public static function new(string $teamId, TokenKey $tokenKey): JWT
    {
        $signer = new Sha256();
        $pk = new Key($tokenKey->getContent());

        $now = new \DateTime();
        $issuedAt = $now->getTimestamp();
        $expiresAt = $now->modify('+1 hour')->getTimestamp();

        $token = (new Builder())
            ->issuedBy($teamId) // (iss claim) - teamId
            ->issuedAt($issuedAt)
            ->expiresAt($expiresAt)
            ->withHeader('kid', $tokenKey->getKeyId())
            ->getToken($signer,  $pk);

        return new self($token);
    }

    public static function parse(string $token): JWT
    {
        return new self((new Parser())->parse($token));
    }

    public function setToken(Token $token): JWT
    {
        $this->token = $token;
        return $this;
    }

    public function getToken(): Token
    {
        return $this->token;
    }

    public function asString(): string
    {
        return (string) $this->token;
    }

    public function hasExpired(): bool
    {
        return $this->token->isExpired();
    }

    public function refresh(TokenKey $tokenKey): JWT
    {
        if ($this->token->isExpired()) {
            $teamId = $this->token->getClaim('iss');

            $signer = new Sha256();
            $pk = new Key($tokenKey->getContent());

            $now = new \DateTime();
            $issuedAt = $now->getTimestamp();
            $expiresAt = $now->modify('+1 hour')->getTimestamp();

            $this->token = (new Builder())
                ->issuedBy($teamId) // (iss claim) - teamId
                ->issuedAt($issuedAt)
                ->expiresAt($expiresAt)
                ->withHeader('kid', $tokenKey->getKeyId())
                ->getToken($signer,  $pk);
        }
        return $this;
    }

    public function getAuthOptions(): array
    {
        return [
            'headers' => [
                Header::AUTHORIZATION => "Bearer $this"
            ]
        ];
    }
}