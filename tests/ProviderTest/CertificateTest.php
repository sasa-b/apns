<?php
/**
 * Created by PhpStorm.
 * User: sasa.blagojevic@mail.com
 * Date: 30/08/2020
 * Time: 17:20
 */

namespace SasaB\Tests\ProviderTest;


use PHPUnit\Framework\TestCase;
use SasaB\Apns\Provider\Certificate;
use SasaB\Tests\CreateCertificateTrust;

class CertificateTest extends TestCase
{
    use CreateCertificateTrust;

    public function testItCanBeCreatedFromFile()
    {
        $certificate = $this->makeCertificate();

        $authOptions = $certificate->getAuthOptions();

        $this->assertArraySubset(['cert' => $certificate->getFilePath()], $authOptions);

        $this->assertArrayHasKey('headers', $authOptions);

        $this->assertArrayHasKey('apns-topic', $authOptions['headers']);
    }

    public function testItThrowsWhenCertFileIsNotFound()
    {
        $this->expectException(\InvalidArgumentException::class);

        Certificate::fromFile('/no-file.txt');
    }
}