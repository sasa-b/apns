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

class CertificateTest extends TestCase
{
    public function testItCanBeCreatedFromFile()
    {
        $file = '';
        $certificate = Certificate::fromFile($file);

    }
}