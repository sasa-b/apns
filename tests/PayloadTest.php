<?php
/**
 * Created by PhpStorm.
 * User: sasa.blagojevic@mail.com
 * Date: 01/09/2020
 * Time: 14:19
 */

namespace Sco\Apns\Tests;


use PHPUnit\Framework\TestCase;
use Sco\Apns\Payload\Alert;
use Sco\Apns\Payload\Aps;

class PayloadTest extends TestCase
{
    public function testAlert(): void
    {
        $alert = new Alert('');

        $data = [
            'title'          => 'Test Title',
            'body'           => 'Test Body',
            'title-loc-key'  => 'Title Localization Key',
            'title-loc-args' => ['These', 'are', 'all', 'test', 'values'],
            'action-loc-key' => 'Test Action Localization Key',
            'loc-key'        => 'Localization Key',
            'loc-args'       => ['These', 'are', 'all', 'localization', 'values'],
            'launch-image'   => base64_encode(random_bytes(36))
        ];

        $alert->setTitle($data['title']);
        $alert->setText($data['body']);
        $alert->setTitleLocKey($data['title-loc-key']);
        $alert->setTitleLocArgs($data['title-loc-args']);
        $alert->setActionLocKey($data['action-loc-key']);
        $alert->setLocKey($data['loc-key']);
        $alert->setLocArgs($data['loc-args']);
        $alert->setLaunchImage($data['launch-image']);

        self::assertSame($data, $alert->jsonSerialize());
        self::assertSame(json_encode($data), json_encode($alert));
    }

    public function testApsWithAlert(): void
    {
        $alertData = [
            'title'          => 'Test Title',
            'body'           => 'Test Body',
            'title-loc-key'  => 'Title Localization Key',
            'title-loc-args' => ['These', 'are', 'all', 'test', 'values'],
            'action-loc-key' => 'Test Action Localization Key',
            'loc-key'        => 'Localization Key',
            'loc-args'       => ['These', 'are', 'all', 'localization', 'values'],
            'launch-image'   => base64_encode(random_bytes(36))
        ];

        $alert = new Alert('Test Body');
        $alert->setTitle($alertData['title']);
        $alert->setTitleLocKey($alertData['title-loc-key']);
        $alert->setTitleLocArgs($alertData['title-loc-args']);
        $alert->setActionLocKey($alertData['action-loc-key']);
        $alert->setLocKey($alertData['loc-key']);
        $alert->setLocArgs($alertData['loc-args']);
        $alert->setLaunchImage($alertData['launch-image']);

        $apsData = [
            'alert'             => $alertData,
            'badge'             => 1,
            'sound'             => 'alarm.aiff',
            'content-available' => 1,
            'category'          => 'general',
            'thread-id'         => md5('hi')
        ];

        $aps = new Aps($alert);
        $aps->setBadge($apsData['badge']);
        $aps->setSound($apsData['sound']);
        $aps->setContentAvailable($apsData['content-available']);
        $aps->setCategory($apsData['category']);
        $aps->setThreadId($apsData['thread-id']);

        self::assertSame(array_merge($apsData, ['alert' => $alertData]), $aps->asArray());
    }
}
