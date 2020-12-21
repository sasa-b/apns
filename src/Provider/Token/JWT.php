<?php
/**
 * Created by PhpStorm.
 * User: sasa.blagojevic@mail.com
 * Date: 30/08/2020
 * Time: 13:02
 */

namespace SasaB\Apns\Provider\Token;


use SasaB\Apns\Header;
use SasaB\Apns\Provider\Trust;


final class JWT implements Trust
{
    private string $encoded;

    public function __construct(
        private string $kid,
        private string $iss,
        private int $iat,
        private int $exp,
        private \OpenSSLAsymmetricKey $pk,
    )
    {
        ['header' => $header, 'claims' => $claims] = $this->asArray();

        $this->encoded = $this->encode($header, $claims);
    }

    public function __toString(): string
    {
        return $this->asString();
    }

    public static function with(string $teamId, Key $tokenKey): JWT
    {
        $now = new \DateTime();

        return new self(
            kid: $tokenKey->getKeyId(),
            iss: $teamId,
            iat: $now->getTimestamp(),
            exp: $now->modify('+1 hour')->getTimestamp(),
            pk: $tokenKey->getOpenSSLPrivateKey(),
        );
    }

    public function asArray(): array
    {
        return [
            'header' => [
                'alg' =>'ES256',
                'kid' => $this->kid
            ],
            'claims' => [
                'iss' => $this->iss,
                'iat' => $this->iat,
                'exp' => $this->exp
            ]
        ];
    }

    public static function parse(string $token, Key $tokenKey): JWT
    {
        $pk = $tokenKey->getOpenSSLPrivateKey();

        $jwt = \Firebase\JWT\JWT::decode($token, $pk, ['ES256']);

        return new self(
            kid: $tokenKey->getKeyId(),
            iss: $jwt->iss,
            iat: $jwt->iat,
            exp: $jwt->exp,
            pk: $pk,
        );
    }

    private function encode(array $header, array $claims): string
    {
        return \Firebase\JWT\JWT::encode($claims, $this->pk, 'ES256', null, $header);
    }

    public function asString(): string
    {
        return $this->encoded;
    }

    public function hasExpired(): bool
    {
        return $this->exp < time();
    }

    public function refresh(): JWT
    {
        if ($this->hasExpired()) {
            $now = new \DateTime();
            $this->iat = $now->getTimestamp();
            $this->exp = $now->modify('+1 hour')->getTimestamp();

            ['header' => $header, 'claims' => $claims] = $this->asArray();

            $this->encoded = $this->encode($header, $claims);
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