<?php
/**
 * Created by PhpStorm.
 * User: sasa.blagojevic@mail.com
 * Date: 30/08/2020
 * Time: 12:41
 */

use SasaB\Apns\Client;
use SasaB\Apns\Notification;
use SasaB\Apns\Provider\JWT;
use SasaB\Apns\Provider\TokenKey;


$tokenKey = new TokenKey('key-id');
$tokenKey->loadFromFile('/file-path');

$jwt = JWT::new('team-id', $tokenKey);

if ($jwt->hasExpired()) {
    $jwt->refresh($tokenKey);
}

$client = Client::auth($jwt);

$notification = new Notification("");

$notification->setCustomKey('mdm', ['PushMagic' => '']);

$client->send($notification);