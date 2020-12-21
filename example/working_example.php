<?php

$keyfile = '../tests/certs/AuthKey.p8';               // Your p8 Key file
$keyid = 'GVN5Q3V6GL';                            // Your Key ID
$teamid = 'X7XGQHWDNM';                           // Your Team ID (see Developer Portal)
$bundleid = 'com.mobileguardian.app';                // Your Bundle ID
$url = 'https://api.push.apple.com';  // development url, or use http://api.push.apple.com for production environment
$token = 'e2c48ed32ef9b018';              // Device Token

$message = '{"aps":{"alert":"Hi there!","sound":"default"}}';

$key = openssl_pkey_get_private(file_get_contents('../tests/certs/AuthKey.p8'));

$header = ['alg'=>'ES256','kid'=>$keyid];
$claims = ['iss'=>$teamid,'iat'=>time()];

$header_encoded = base64($header);
$claims_encoded = base64($claims);

$signature = '';
openssl_sign($header_encoded . '.' . $claims_encoded, $signature, $key, 'sha256');
$jwt = $header_encoded . '.' . $claims_encoded . '.' . base64_encode($signature);

// only needed for PHP prior to 5.5.24
if (!defined('CURL_HTTP_VERSION_2_0')) {
    define('CURL_HTTP_VERSION_2_0', 3);
}

$http2ch = curl_init();
curl_setopt_array($http2ch, array(
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_2_0,
    CURLOPT_URL => "$url/3/device/$token",
    CURLOPT_PORT => 443,
    CURLOPT_HTTPHEADER => array(
        "apns-topic: {$bundleid}",
        "authorization: bearer $jwt"
    ),
    CURLOPT_POST => TRUE,
    CURLOPT_POSTFIELDS => $message,
    CURLOPT_RETURNTRANSFER => TRUE,
    CURLOPT_TIMEOUT => 30,
    CURLOPT_HEADER => 1
));

$result = curl_exec($http2ch);
if ($result === FALSE) {
    throw new Exception("Curl failed: ".curl_error($http2ch));
}

$status = curl_getinfo($http2ch, CURLINFO_HTTP_CODE);
echo $status;
echo curl_multi_getcontent($http2ch);

function base64($data) {
    return rtrim(strtr(base64_encode(json_encode($data)), '+/', '-_'), '=');
}
