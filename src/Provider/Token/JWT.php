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
    /**
     * @var string
     */
    private $kid;
    /**
     * @var string
     */
    private $iss;
    /**
     * @var int
     */
    private $iat;
    /**
     * @var int
     */
    private $exp;
    /**
     * @var resource
     */
    private $pk;
    /**
     * @var string|null
     */
    private $encoded;

    /**
     * JWT constructor.
     * @param string $kid
     * @param string $iss
     * @param int $iat
     * @param int $exp
     * @param resource $pk
     */
    public function __construct(string $kid, string $iss, int $iat, int $exp, $pk)
    {
        $this->kid = $kid;
        $this->iss = $iss;
        $this->iat = $iat;
        $this->exp = $exp;

        if (!is_resource($pk)) {
            throw new Exception("Invalid private key argument. Must be a resource.");
        }
        $this->pk = $pk;

        $this->encode();
    }

    public function __toString(): string
    {
        return $this->asString();
    }

    public static function with(string $teamId, Key $tokenKey): JWT
    {
        $now = new \DateTime();

        return new self(
            $tokenKey->getKeyId(),
            $teamId,
            $now->getTimestamp(),
            $now->modify('+1 hour')->getTimestamp(),
            $tokenKey->getOpenSSLPrivateKey()
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
            $tokenKey->getKeyId(),
            $jwt->iss,
            $jwt->iat,
            $jwt->exp,
            $pk
        );
    }

    private function encode()
    {
        $payload = $this->asArray();

        $this->encoded = \Firebase\JWT\JWT::encode($payload['claims'], $this->pk, 'ES256', null, $payload['header']);
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
            $this->encode();
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