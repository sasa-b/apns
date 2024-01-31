<?php
/**
 * Created by PhpStorm.
 * User: sasa.blagojevic@mail.com
 * Date: 30/08/2020
 * Time: 12:41
 */

require dirname(__DIR__).'/vendor/autoload.php';

use Ramsey\Uuid\Uuid;
use Sco\Apns\Client;
use Sco\Apns\Notification;
use Sco\Apns\Provider\Token\JWT;
use Sco\Apns\Provider\Token\Key;

$keyId = file_get_contents('../tests/certs/key-id.txt');
$teamId = file_get_contents('../tests/certs/team-id.txt');

$tokenKey = Key::loadFromFile('../tests/certs/AuthKey.p8', $keyId);

$jwt = JWT::with($teamId, $tokenKey);

if ($jwt->hasExpired()) {
    $jwt->refresh();
}

$client = Client::auth($jwt);

$notification = new Notification("51d5f3696c9cc62caf322fbcfd0b25a455697b1c3261eb4ed085041c6e895bdb");

$notification->setApsId($apsId = Uuid::uuid4());

$notification->setPushTopic('com.vendor.app');

$notification->setCustomKey('mdm', '4DA9FEC7-5443-48B3-9491-892F1147BE47');

$response = $client->send($notification);

echo (string) $apsId."\n";
echo (string) $response->getApnsId()."\n";
echo $response;
