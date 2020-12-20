<?php
/**
 * Created by PhpStorm.
 * User: sasa.blagojevic@mail.com
 * Date: 28/08/2020
 * Time: 15:16
 */

namespace SasaB\Apns;


interface Header
{
    public const APNS_ID = 'apns-id';
    public const APNS_EXPIRATION = 'apns-expiration';
    public const APNS_PRIORITY = 'apns-priority';
    public const APNS_TOPIC = 'apns-topic';
    public const APNS_COLLAPSE_ID = 'apns-collapse-id';
    public const APNS_PUSH_TYPE = 'apns-push-type';

    public const AUTHORIZATION = 'authorization';
}