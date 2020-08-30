<?php
/**
 * Created by PhpStorm.
 * User: sasa.blagojevic@mail.com
 * Date: 30/08/2020
 * Time: 21:28
 */

namespace SasaB\Apns\Payload;


interface ApsKey
{
    const ALERT = 'alert';
    const BADGE = 'badge';
    const SOUND = 'sound';
    const CONTENT_AVAILABLE = 'content-available';
    const MUTABLE_CONTENT = 'mutable-content';
    const CATEGORY = 'category';
    const THREAD_ID = 'thread-id';
}