<?php
/**
 * Created by PhpStorm.
 * User: sasa.blagojevic@mail.com
 * Date: 27/08/2020
 * Time: 14:39
 */

namespace SasaB\Apns;

use SasaB\Provider\Trust;

final class Certificate implements Trust
{
    public static function fromFile(string $path): Certificate
    {
        return new self();
    }
}