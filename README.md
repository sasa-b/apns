# apns
APNS - Apple Push Notification Service PHP Client

[![PHP >= 7.0](https://img.shields.io/badge/php-%3E%3D%207.0-8892BF.svg?style=flat-square)](https://php.net/)
![](https://img.shields.io/packagist/v/sasa-b/apns2)
![](https://img.shields.io/packagist/l/sasa-b/apns2)

## Requirements
```
PHP >= 7.0
lib-curl >= 7.46.0 (with http/2 support enabled)
lib-openssl >= 1.0.2e
```

In case of PHP 7.0 you will need to compile PHP from source with the newer lib-curl which supports HTTP2 requests:
```
./configure --with-curl=/usr/local/include/curl --with-libdir=lib64 --with-openssl ...

./make
```


## Installation
```
composer require sasa-b/apns2
```

## Getting Started

### Certificate Provider Trust

```php
require '/vendor/autoload.php';

use SasaB\Apns\Client;
use SasaB\Apns\Provider\Certificate;

use SasaB\Apns\Payload\Aps;
use SasaB\Apns\Payload\Alert;
use SasaB\Apns\Notification;

$certificate = Certificate::fromFile('/PushCert.pem');

$client = Client::auth($certificate);

$notification = new Notification("{device_token}");

$notification->setAps(new Aps(new Alert('Hello World')));

$response = $client->send($notification);

echo (string) $response->getApnsId()."\n";
echo $response;
```

### Token Key Provider Trust

```php
require '/vendor/autoload.php';

use SasaB\Apns\Client;
use SasaB\Apns\Provider\JWT;
use SasaB\Apns\Provider\TokenKey;

use SasaB\Apns\Payload\Aps;
use SasaB\Apns\Payload\Alert;
use SasaB\Apns\Notification;

$tokenKey = new TokenKey('{token_key}');
$tokenKey->loadFromFile('/AuthKey.p8');

$jwt = JWT::new('{team_id}', $tokenKey);

if ($jwt->hasExpired()) {
    $jwt->refresh($tokenKey);
}

$client = Client::auth($jwt);

$notification = new Notification("{device_token}");

$notification->setPushTopic('com.vendor.app');

$notification->setAps(new Aps(new Alert('Hello World')));

$response = $client->send($notification);

echo (string) $response->getApnsId()."\n";
echo $response;
```