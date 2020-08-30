<?php
/**
 * Created by PhpStorm.
 * User: sasa.blagojevic@mail.com
 * Date: 30/08/2020
 * Time: 21:23
 */

namespace SasaB\Apns\Payload;


interface AlertKey
{
    const TITLE = 'title';
    const SUBTITLE = 'subtitle';
    const BODY = 'body';
    const TITLE_LOC_KEY = 'title-loc-key';
    const TITLE_LOC_ARGS = 'title-loc-args';
    const ACTION_LOC_KEY = 'action-loc-key';
    const LOC_KEY = 'loc-key';
    const LOC_ARGS = 'loc-args';
    const LAUNCH_IMAGE = 'launch-image';
}