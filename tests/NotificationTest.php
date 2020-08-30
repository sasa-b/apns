<?php
/**
 * Created by PhpStorm.
 * User: sasa.blagojevic@mail.com
 * Date: 30/08/2020
 * Time: 16:59
 */

namespace SasaB\Tests;


use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;
use SasaB\Apns\Notification;
use SasaB\Apns\Payload\Alert;
use SasaB\Apns\Payload\Aps;

class NotificationTest extends TestCase
{
    public function testItCanSetApsId()
    {
        $message = new Notification('device-token');

        $uuid = Uuid::uuid4();

        $message->setApsId($uuid);

        $this->assertSame((string) $uuid, (string) $message->getApsId());
    }

    public function testItCanSetApsWithSimpleAlertPayload()
    {
        $aps = new Aps(new Alert('Hello World'));

        $message = new Notification('device-token');

        $message->setAps($aps);

        $this->assertArrayHasKey('aps', $message->getPayload());
        $this->assertSame($aps->asArray(), $message->getPayload()['aps']);
        $this->assertSame(json_encode(['aps' => $aps]), json_encode($message));
    }

    public function testItCanSetApsWithAlertPayload()
    {
        $alert = new Alert('Hello World Text', 'Hello World Title');

        $aps = new Aps($alert);

        $message = new Notification('device-token');

        $message->setAps($aps);

        $this->assertArrayHasKey('aps', $message->getPayload());
        $this->assertArrayHasKey('title', $alert->asArray());
        $this->assertSame($aps->asArray(), $message->getPayload()['aps']);
        $this->assertSame(json_encode(['aps' => $aps]), json_encode($message));
    }

    public function testItCanSetCustomPayload()
    {
        $message = new Notification('device-token');

        $message->setCustom(['acme' => 'baz']);
        $message->setCustomKey('foo', 'bar');

        $payload = $message->getCustom();

        $this->assertEquals($payload, ['acme' => 'baz', 'foo' => 'bar']);
        $this->assertSame(json_encode($payload), (string) $message);
    }

    public function testItCanSetMdmPayload()
    {
        $mdmPayload = ['mdm' => ['PushMagic' => 'xxx-xxx-xxx-xxx']];

        $message = new Notification('device-token');

        $message->setCustomKey('mdm', ['PushMagic' => 'xxx-xxx-xxx-xxx']);

        $this->assertInstanceOf(UuidInterface::class, $message->getApsId());
        $this->assertSame($mdmPayload, $message->getCustom());

        $this->assertSame(json_encode($mdmPayload), (string) $message);
    }
}