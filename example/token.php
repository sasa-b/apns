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

$notification = new Notification("51d5f3696c9cc62caf322fbcfd0b25a455697b1c3261eb4ed085041c6e895bdb");

$notification->setCustomKey('mdm', '4DA9FEC7-5443-48B3-9491-892F1147BE47');

$client->send($notification);