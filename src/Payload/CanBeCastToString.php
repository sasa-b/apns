<?php
/**
 * Created by PhpStorm.
 * User: sasa.blagojevic@mail.com
 * Date: 30/08/2020
 * Time: 17:36
 */

namespace SasaB\Apns\Payload;


trait CanBeCastToString
{
    public function __toString()
    {
        $encoded = json_encode($this);

        return $encoded === false ? json_last_error_msg() : $encoded;
    }
}