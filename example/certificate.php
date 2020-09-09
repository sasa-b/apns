<?php
/**
 * Created by PhpStorm.
 * User: sasa.blagojevic@mail.com
 * Date: 30/08/2020
 * Time: 12:41
 */

require dirname(__DIR__).'/vendor/autoload.php';

use SasaB\Apns\Provider\Certificate;
use SasaB\Apns\Client;
use SasaB\Apns\Notification;
use \Ramsey\Uuid\Uuid;

$certificate = Certificate::fromFile('../tests/certs/PushCert.pem');

$client = Client::auth($certificate);

$notification = new Notification("51d5f3696c9cc62caf322fbcfd0b25a455697b1c3261eb4ed085041c6e895bdb");

$notification->setApsId($apsId = Uuid::uuid4());

$notification->setCustomKey('mdm', '4DA9FEC7-5443-48B3-9491-892F1147BE47');


$response = $client->send($notification);

echo (string) $apsId."\n";
echo (string) $response->getApnsId()."\n";
echo $response;