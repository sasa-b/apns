<?php
/**
 * Created by PhpStorm.
 * User: sasa.blagojevic@mail.com
 * Date: 30/08/2020
 * Time: 17:20
 */

namespace Sco\Apns\Tests\ProviderTest;


use PHPUnit\Framework\TestCase;
use Sco\Apns\Provider\Certificate;
use Sco\Apns\Tests\CreateCertificateTrust;

class CertificateTest extends TestCase
{
    use CreateCertificateTrust;

    public function testItCanBeCreatedFromFile()
    {
        $certificate = $this->makeCertificate();

        $authOptions = $certificate->getAuthOptions();

        self::assertArrayHasKey('cert', $authOptions);

        self::assertSame($certificate->getFilePath(), $authOptions['cert']);

        self::assertArrayHasKey('headers', $authOptions);

        self::assertArrayHasKey('apns-topic', $authOptions['headers']);
    }

    public function testItThrowsWhenCertFileIsNotFound()
    {
        $this->expectException(\InvalidArgumentException::class);

        Certificate::fromFile('/no-file.txt');
    }
}
