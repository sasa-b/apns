<?php
/**
 * Created by PhpStorm.
 * User: sasa.blagojevic@mail.com
 * Date: 30/08/2020
 * Time: 12:41
 */

use SasaB\Apns\Provider\Certificate;
use SasaB\Apns\Client;
use SasaB\Apns\Notification;

$certificate = Certificate::fromFile('/');

$client = Client::auth($certificate);

$notification = new Notification("");

$notification->setCustomKey('mdm', ['PushMagic' => '']);

$client->send($notification);