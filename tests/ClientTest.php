<?php
/**
 * Created by PhpStorm.
 * User: sasa.blagojevic@mail.com
 * Date: 30/08/2020
 * Time: 17:00
 */

namespace SasaB\Apns\Tests;


use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;
use SasaB\Apns\Client;
use SasaB\Apns\Notification;
use SasaB\Apns\Provider\Token\JWT;

class ClientTest extends TestCase
{
    use CreateCertificateTrust, CreateTokenKeyTrust;

    public function testItCanSendCertificateNotifications()
    {
        $certificate = $this->makeCertificate();

        $client = Client::auth($certificate);

        $notification = new Notification("51d5f3696c9cc62caf322fbcfd0b25a455697b1c3261eb4ed085041c6e895bdb");

        $notification->setApsId($apsId = Uuid::uuid4());

        $notification->setCustomKey('mdm', '4DA9FEC7-5443-48B3-9491-892F1147BE47');

        $response = $client->send($notification);

        self::assertSame((string) $apsId, (string) $response->getApnsId());
        self::assertSame(200, $response->getCode());
    }

    public function testItCanSendTokenNotifications()
    {
        $testsDir = __DIR__.DIRECTORY_SEPARATOR;

        $tokenKey = $this->makeTokenKey();

        $jwt = JWT::with($this->teamId, $tokenKey);

        if ($jwt->hasExpired()) {
            $jwt->refresh();
        }

        $client = Client::auth($jwt);

        $notification = new Notification("51d5f3696c9cc62caf322fbcfd0b25a455697b1c3261eb4ed085041c6e895bdb");

        $notification->setApsId($apsId = Uuid::uuid4());

        $notification->setPushTopic(file_get_contents($testsDir.'certs/apns-topic.txt'));

        $notification->setCustomKey('mdm', '4DA9FEC7-5443-48B3-9491-892F1147BE47');

        $response = $client->send($notification);

        self::assertSame((string) $apsId, (string) $response->getApnsId());
        self::assertSame(200, $response->getCode());
    }
}