<?php
/**
 * Created by PhpStorm.
 * User: sasa.blagojevic@mail.com
 * Date: 30/08/2020
 * Time: 13:02
 */

namespace SasaB\Apns\Provider;

use Lcobucci\JWT\Builder;
use Lcobucci\JWT\Configuration;
use Lcobucci\JWT\Parser;
use Lcobucci\JWT\Signer\Ecdsa\MultibyteStringConverter;
use Lcobucci\JWT\Signer\Key;
use Lcobucci\JWT\Signer\Ecdsa\Sha256;
use Lcobucci\JWT\Token;
use SasaB\Apns\Header;


final class JWT implements Trust
{
    public function __construct(
        private Token $token,
        private Configuration $config
    ) {}

    public function __toString(): string
    {
        return $this->asString();
    }

    public static function new(string $teamId, TokenKey $tokenKey): JWT
    {
        $config = Configuration::forAsymmetricSigner(
            new Sha256(new MultibyteStringConverter()),
            Key\LocalFileReference::file($tokenKey->getFile()),
            Key\InMemory::empty()
        );

        $signer = $config->signer();
        $pk = $config->signingKey();

        $issuedAt = new \DateTimeImmutable();
        $expiresAt = \DateTimeImmutable::createFromMutable(
            (new \DateTime())->modify('+1 hour')
        );

        $token = ($config->builder())
            ->issuedBy($teamId) // (iss claim) - teamId
            ->issuedAt($issuedAt)
            ->expiresAt($expiresAt)
            ->withHeader('kid', $tokenKey->getKeyId())
            ->getToken($signer,  $pk);

        return new self($token, $config);
    }

    public static function parse(string $token, TokenKey $tokenKey): JWT
    {
        $config = Configuration::forAsymmetricSigner(
            new Sha256(new MultibyteStringConverter()),
            Key\LocalFileReference::file($tokenKey->getFile()),
            Key\InMemory::empty()
        );

        $parser = $config->parser();

        return new self($parser->parse($token), $config);
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
        return $this->token->toString();
    }

    public function hasExpired(): bool
    {
        return $this->token->isExpired(new \DateTimeImmutable());
    }

    public function refresh(TokenKey $tokenKey): JWT
    {
        if ($this->hasExpired()) {
            $teamId = $this->token->claims()->get('iss');

            $signer = $this->config->signer();
            $pk = $this->config->signingKey();

            $issuedAt = new \DateTimeImmutable();
            $expiresAt = \DateTimeImmutable::createFromMutable(
                (new \DateTime())->modify('+1 hour')
            );

            $this->token = ($this->config->builder())
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