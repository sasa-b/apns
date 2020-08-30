<?php
/**
 * Created by PhpStorm.
 * User: sasa.blagojevic@mail.com
 * Date: 28/08/2020
 * Time: 15:16
 */

namespace SasaB\Apns;


interface Headers
{
    const APNS_ID = 'apns-id';
    const APNS_EXPIRATION = 'apns-expiration';
    const APNS_PRIORITY = 'apns-priority';
    const APNS_TOPIC = 'apns-topic';
    const APNS_COLLAPSE_ID = 'apns-collapse-id';
    const APNS_PUSH_TYPE = 'apns-push-type';

    const AUTHORIZATION = 'authorization';
}