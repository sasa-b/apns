<?php
/**
 * Created by PhpStorm.
 * User: sasa.blagojevic@mail.com
 * Date: 09/09/2020
 * Time: 18:08
 */

namespace Sco\Apns\Tests;


use Sco\Apns\Provider\Certificate;

trait CreateCertificateTrust
{
    protected function makeCertificate(): Certificate
    {
        $filePath = __DIR__.DIRECTORY_SEPARATOR.'certs/PushCert.pem';

        return Certificate::fromFile($filePath);
    }
}
