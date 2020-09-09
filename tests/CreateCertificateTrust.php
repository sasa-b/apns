<?php
/**
 * Created by PhpStorm.
 * User: sasa.blagojevic@mail.com
 * Date: 09/09/2020
 * Time: 18:08
 */

namespace SasaB\Tests;


use SasaB\Apns\Provider\Certificate;

trait CreateCertificateTrust
{
    protected function makeCertificate(): Certificate
    {
        $filePath = 'certs/PushCert.pem';

        return Certificate::fromFile($filePath);
    }
}