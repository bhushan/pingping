# PingPing

This composer package allows us to easily integrate `PingPing` APIs in your `Laravel` project.

> What is PingPing ?
>
> PingPing is the simplest uptime monitoring service in the world to get notified, when your website is down or your certificate becomes invalid. No more, no less.

## Installation

### Step 1: Composer

Firstly require this package using following command.

```bash
composer require enlight/pingping
```

### Step 2: Service Provider (Optional)

This package support's auto discovery but for any reason you need to add `ServiceProvider`
into `providers` array manually then check follow steps below.

Open `config/app.php` and, within the `providers` array, append:

```php
Enlight\PingPing\Providers\PingPingServiceProvider::class
```

This will bootstrap the package into Laravel.

### Step 3: Set Up Environment

Check your `.env` file, and ensure that your `PING_PING_API_TOKEN` is set with valid token.

> You can get the token from below link and make sure it is enabled.
>
> https://pingping.io/account/settings

You are all set to use it.

### Step 4: Publish Configuration (Optional)

Optionally, You can publish configuration file, so you can modify defaults values.

To Publish Configuration Run

```bash
php artisan vendor:publish --provider="Enlight\PingPing\Providers\PingPingServiceProvider" --tag="config"
```

Exported config you can find in `/config` folder as `pingping.php`.

## Usage

All methods and API calls will return `Illuminate\Http\Client\Response` instance. That mean's, you have access to
following methods.

```php
$response->body() : string;
$response->json() : array|mixed;
$response->collect() : Illuminate\Support\Collection;
$response->status() : int;
$response->ok() : bool;
$response->successful() : bool;
$response->failed() : bool;
$response->serverError() : bool;
$response->clientError() : bool;
$response->header($header) : string;
$response->headers() : array;
```

### Retrieve all websites (monitors)

```php
    use Enlight\PingPing\Client;
    
    public function index(Client $client)
    {
        $response = $client->monitors();

        if ($response->failed()) {
            return $response->json();
        }

        return $response->json();
    }
```

### Retrieve a specific website (monitor)

```php
    use Enlight\PingPing\Client;
    
    public function show($id, Client $client)
    {
        $response = $client->monitors((int) $id);

        if ($response->failed()) {
            return $response->json();
        }

        return $response->json();
    }
```

### Retrieve statistics from a specific website (monitor)

```php
    use Enlight\PingPing\Client;
    use Enlight\PingPing\Exceptions\MonitorIDRequiredException;
    
    /**
     * @throws MonitorIDRequiredException
     */
    public function show($id, Client $client)
    {
        $response = $client->statistics((int) $id);

        if ($response->failed()) {
            return $response->json();
        }

        return $response->json();
    }
```

### Create a website (monitor)

```php
    use Enlight\PingPing\Client;
    use Enlight\PingPing\Exceptions\ValidUrlRequiredException;
   
    /**
     * @throws ValidUrlRequiredException
     */
    public function store(Client $client)
    {
        $url = 'https://my-cool-website.test';

        $response = $client->createMonitor($url);

        if ($response->failed()) {
            return $response->json();
        }

        return $response->json();
    }
```

### Update a website (monitor)

```php
    use Enlight\PingPing\Client;
    use Enlight\PingPing\Exceptions\AliasRequiredException;
    use Enlight\PingPing\Exceptions\ValidUrlRequiredException;
    use Enlight\PingPing\Exceptions\MonitorIDRequiredException;
    
    /**
     * @throws AliasRequiredException
     * @throws ValidUrlRequiredException
     * @throws MonitorIDRequiredException
     */
    public function update($id, Client $client)
    {
        $url = 'https://my-cool-website2.test';
        $alias = 'My cool website2';

        $response = $client->updateMonitor((int) $id, $url, $alias);

        if ($response->failed()) {
            return $response->json();
        }

        return $response->json();
    }
```

### Delete a website (monitor)

```php
    use Enlight\PingPing\Client;
    use Enlight\PingPing\Exceptions\MonitorIDRequiredException;
    
    /**
     * @throws MonitorIDRequiredException
     */
    public function destroy($id, Client $client)
    {
        $response = $client->deleteMonitor((int) $id);

        if ($response->failed()) {
            return $response->json();
        }

        return $response->json();
    }
```

Finally, your updated controllers might look like this now.

```php
<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use Enlight\PingPing\Client;
use Enlight\PingPing\Exceptions\AliasRequiredException;
use Enlight\PingPing\Exceptions\ValidUrlRequiredException;
use Enlight\PingPing\Exceptions\MonitorIDRequiredException;

class MonitorController extends Controller
{
    /** @var Client */
    private $client;

    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    public function index()
    {
        $response = $this->client->monitors();

        if ($response->failed()) {
            return $response->json();
        }

        return $response->json();
    }

    public function show($id)
    {
        $response = $this->client->monitors((int) $id);

        if ($response->failed()) {
            return $response->json();
        }

        return $response->json();
    }

    /**
     * @throws ValidUrlRequiredException
     */
    public function store()
    {
        $url = 'https://my-cool-website.test';

        $response = $this->client->createMonitor($url);

        if ($response->failed()) {
            return $response->json();
        }

        return $response->json();
    }

    /**
     * @throws AliasRequiredException
     * @throws ValidUrlRequiredException
     * @throws MonitorIDRequiredException
     */
    public function update($id)
    {
        $url = 'https://my-cool-website2.test';
        $alias = 'My cool website2';

        $response = $this->client->updateMonitor((int) $id, $url, $alias);

        if ($response->failed()) {
            return $response->json();
        }

        return $response->json();
    }

    /**
     * @throws MonitorIDRequiredException
     */
    public function destroy($id)
    {
        $response = $this->client->deleteMonitor((int) $id);

        if ($response->failed()) {
            return $response->json();
        }

        return $response->json();
    }
}
```

```php
<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use Enlight\PingPing\Client;
use Enlight\PingPing\Exceptions\MonitorIDRequiredException;

class StatisticsController extends Controller
{
    /**
     * @throws MonitorIDRequiredException
     */
    public function show($id, Client $client)
    {
        $response = $client->statistics((int) $id);

        if ($response->failed()) {
            return $response->json();
        }

        return $response->json();
    }
}
```

### Contributors

This package is inspired by the [Steve's](https://github.com/juststeveking) blog post on
[Working with third party services in Laravel](https://www.juststeveking.uk/working-with-third-party-services-in-laravel)
. I highly recommend you to read it.

Thank You :)

Happy Coding.

