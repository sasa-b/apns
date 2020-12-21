<?php
/**
 * Created by PhpStorm.
 * User: sasa.blagojevic@mail.com
 * Date: 30/08/2020
 * Time: 16:59
 */

namespace SasaB\Apns\Tests;


use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;
use SasaB\Apns\Notification;
use SasaB\Apns\Payload\Alert;
use SasaB\Apns\Payload\Aps;

class NotificationTest extends TestCase
{
    public function testItCanSetApsId(): void
    {
        $message = new Notification('device-token');

        $uuid = Uuid::uuid4();

        $message->setApsId($uuid);

        self::assertSame((string) $uuid, (string) $message->getApsId());
    }

    public function testItCanSetApsWithSimpleAlertPayload(): void
    {
        $aps = new Aps(new Alert('Hello World'));

        $message = new Notification('device-token');

        $message->setAps($aps);

        self::assertArrayHasKey('aps', $message->getPayload());
        self::assertSame($aps->asArray(), $message->getPayload()['aps']);
        self::assertSame(json_encode(['aps' => $aps]), json_encode($message));
    }

    public function testItCanSetApsWithAlertPayload(): void
    {
        $alert = new Alert('Hello World Text', 'Hello World Title');

        $aps = new Aps($alert);

        $message = new Notification('device-token');

        $message->setAps($aps);

        self::assertArrayHasKey('aps', $message->getPayload());
        self::assertArrayHasKey('title', $alert->asArray());
        self::assertSame($aps->asArray(), $message->getPayload()['aps']);
        self::assertSame(json_encode(['aps' => $aps]), json_encode($message));
    }

    public function testItCanSetCustomPayload(): void
    {
        $message = new Notification('device-token');

        $message->setCustom(['acme' => 'baz']);
        $message->setCustomKey('foo', 'bar');

        $payload = $message->getCustom();

        self::assertEquals(['acme' => 'baz', 'foo' => 'bar'], $payload);
        self::assertSame(json_encode($payload), (string) $message);
    }

    public function testItCanSetMdmPayload(): void
    {
        $mdmPayload = ['mdm' => 'xxx-xxx-xxx-xxx'];

        $message = new Notification('device-token');

        $message->setCustomKey('mdm', 'xxx-xxx-xxx-xxx');

        self::assertInstanceOf(UuidInterface::class, $message->getApsId());
        self::assertSame($mdmPayload, $message->getCustom());

        self::assertSame(json_encode($mdmPayload), (string) $message);
    }
}