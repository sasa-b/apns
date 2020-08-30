<?php
/**
 * Created by PhpStorm.
 * User: sasa.blagojevic@mail.com
 * Date: 27/08/2020
 * Time: 14:39
 */

namespace SasaB\Apns\Provider;

interface Trust
{
    public function getAuthOptions(): array;
}