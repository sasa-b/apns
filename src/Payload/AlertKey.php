<?php
/**
 * Created by PhpStorm.
 * User: sasa.blagojevic@mail.com
 * Date: 30/08/2020
 * Time: 21:23
 */

namespace SasaB\Apns\Payload;


interface AlertKeys
{
    const TITLE = 'title';
    const SUBTITLE = 'subtitle';
    const BODY = 'body';
    const TITLE_LOC = 'title-loc-key';
    const TITLE_LOC_ARGS = 'title-loc-args';
    const ACTION_LOC = 'action-loc-key';
    const LOC = 'loc-key';
    const LOC_ARGS = 'loc-args';
    const LAUNCH_IMAGE = 'launch-image';
}