# apns
APNS - Apple Push Notification Service PHP Client

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