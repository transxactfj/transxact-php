# Transxact PHP Library

[![fern shield](https://img.shields.io/badge/%F0%9F%8C%BF-Built%20with%20Fern-brightgreen)](https://buildwithfern.com?utm_source=github&utm_medium=github&utm_campaign=readme&utm_source=https%3A%2F%2Fgithub.com%2Ftransxactfj%2Ftransxact-php)
[![php shield](https://img.shields.io/badge/php-packagist-pink)](https://packagist.org/packages/transxact/transxact)

Create a Checkout Session on your server, send the Customer to its hostedUrl, and fulfil the order from a verified webhook. Quickstart: https://docs.transxact.io/guides/quickstart. Step-by-step recipe for you or your coding agent: https://docs.transxact.io/guides/integrate-with-an-ai-agent

## Table of Contents

- [Documentation](#documentation)
- [Requirements](#requirements)
- [Installation](#installation)
- [Verifying Webhooks](#verifying-webhooks)
- [Usage](#usage)
- [Environments](#environments)
- [Exception Handling](#exception-handling)
- [Advanced](#advanced)
  - [Custom Client](#custom-client)
  - [Retries](#retries)
  - [Timeouts](#timeouts)
- [Contributing](#contributing)

## Documentation

API reference documentation is available [here](https://docs.transxact.io/api-reference).

## Requirements

This SDK requires PHP ^8.1.

## Installation

```sh
composer require transxact/transxact
```

## Verifying webhooks

Check the `Transxact-Signature` header before trusting a webhook, or anyone who can reach your endpoint can forge a payment notification. Pass the raw request body, exactly as received, not re-serialized JSON. Signatures older than 5 minutes are rejected.

```php
<?php

use Transxact\Webhooks;

$rawBody = file_get_contents('php://input');
$ok = Webhooks::verifySignature(
    $rawBody,
    $_SERVER['HTTP_TRANSXACT_SIGNATURE'] ?? '',
    (string) getenv('TRANSXACT_WEBHOOK_SECRET'),
);
if (!$ok) {
    http_response_code(400);
    exit;
}

$event = json_decode($rawBody, true);
// Fulfil on $event['type'] === 'checkout_session.succeeded', skipping event ids you've already handled.
http_response_code(200);
```

Your signing secret is in the **Webhooks** section of the dashboard. See [Verifying webhooks](https://docs.transxact.io/guides/verifying-webhooks) for retries and duplicate events.


## Usage

Instantiate and use the client with the following:

```php
<?php

namespace Example;

use Transxact\TransxactClient;
use Transxact\CheckoutSessions\Requests\CreateCheckoutSessionRequest;
use Transxact\CheckoutSessions\Types\CreateCheckoutSessionRequestCurrency;

$client = new TransxactClient(
    token: '<token>',
);
$client->checkoutSessions->create(
    new CreateCheckoutSessionRequest([
        'idempotencyKey' => 'a1b2c3d4-order-9912',
        'amount' => 5000,
        'currency' => CreateCheckoutSessionRequestCurrency::Fjd->value,
    ]),
);

```

## Environments

This SDK allows you to configure different environments for API requests.

```php
The SDK defaults to the `Production` environment. To use a different environment, pass it to the client constructor:

```php
use Transxact\TransxactClient;
use Transxact\Environments;

$client = new TransxactClient(
    token: '<YOUR_TOKEN>',
    options: [
        'baseUrl' => Environments::Staging->value
    ]
);
```

Available environments:
- `Environments::Production`
```

## Exception Handling

When the API returns a non-success status code (4xx or 5xx response), an exception will be thrown.

```php
use Transxact\Exceptions\TransxactApiException;
use Transxact\Exceptions\TransxactException;

try {
    $response = $client->checkoutSessions->create(...);
} catch (TransxactApiException $e) {
    echo 'API Exception occurred: ' . $e->getMessage() . "\n";
    echo 'Status Code: ' . $e->getCode() . "\n";
    echo 'Response Body: ' . $e->getBody() . "\n";
    // Optionally, rethrow the exception or handle accordingly.
}
```

## Advanced

### Custom Client

This SDK is built to work with any HTTP client that implements the [PSR-18](https://www.php-fig.org/psr/psr-18/) `ClientInterface`.
By default, if no client is provided, the SDK will use `php-http/discovery` to find an installed HTTP client.
However, you can pass your own client that adheres to `ClientInterface`:

```php
use Transxact\TransxactClient;

// Pass any PSR-18 compatible HTTP client implementation.
// For example, using Guzzle:
$customClient = new \GuzzleHttp\Client([
    'timeout' => 5.0,
]);

$client = new TransxactClient(options: [
    'client' => $customClient
]);

// Or using Symfony HttpClient:
// $customClient = (new \Symfony\Component\HttpClient\Psr18Client())
//     ->withOptions(['timeout' => 5.0]);
//
// $client = new TransxactClient(options: [
//     'client' => $customClient
// ]);
```

### Retries

The SDK is instrumented with automatic retries with exponential backoff. A request will be retried as long
as the request is deemed retryable and the number of retry attempts has not grown larger than the configured
retry limit (default: 2).

A request is deemed retryable when any of the following HTTP status codes is returned:

- [408](https://developer.mozilla.org/en-US/docs/Web/HTTP/Status/408) (Timeout)
- [429](https://developer.mozilla.org/en-US/docs/Web/HTTP/Status/429) (Too Many Requests)
- [5XX](https://developer.mozilla.org/en-US/docs/Web/HTTP/Status#server_error_responses) (Internal Server Error)

The `retryStatusCodes` configuration controls which [5XX](https://developer.mozilla.org/en-US/docs/Web/HTTP/Status#server_error_responses) status codes are retried:

- `legacy` (default): Retries `408`, `429`, and all `>= 500`
- `recommended`: Retries `408`, `429`, `502`, `503`, `504` only (excludes `500 Internal Server Error` to avoid retrying non-idempotent failures)

Use the `maxRetries` request option to configure this behavior.

```php
$response = $client->checkoutSessions->create(
    ...,
    options: [
        'maxRetries' => 0 // Override maxRetries at the request level
    ]
);
```

### Timeouts

The SDK defaults to a 30 second timeout. Use the `timeout` option to configure this behavior.

```php
$response = $client->checkoutSessions->create(
    ...,
    options: [
        'timeout' => 3.0 // Override timeout at the request level
    ]
);
```

## Contributing

While we value open-source contributions to this SDK, this library is generated programmatically.
Additions made directly to this library would have to be moved over to our generation code,
otherwise they would be overwritten upon the next generated release. Feel free to open a PR as
a proof of concept, but know that we will not be able to merge it as-is. We suggest opening
an issue first to discuss with us!

On the other hand, contributions to the README are always very welcome!
