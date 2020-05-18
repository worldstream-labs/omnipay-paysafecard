# Omnipay: Paysafecard

[![Build Status](https://travis-ci.org/worldstream-labs/omnipay-paysafecard.svg?branch=master)](https://travis-ci.org/worldstream-labs/omnipay-paysafecard)

Paysafecard library for the Omnipay V3 payment library

## Installation
Use composer to add the library as dependency for your project
`composer require worldstream-labs/omnipay-paysafecard`

## Development
To set up for development:  
`composer install`

## Usage

### Setup
```php
<?php

require 'vendor/autoload.php';

use Omnipay\Omnipay;

$gateway = Omnipay::create('Paysafecard');
$gateway->setApiKey('yourApiKey');

// When deploying to production, don't forget to set test mode to false
$gateway->setTestMode(false);

```

### Authorizing a payment
```php
<?php

require 'vendor/autoload.php';

use Omnipay\Omnipay;

$gateway = Omnipay::create('Paysafecard');
$gateway->setApiKey('yourApiKey');

$response = $gateway->authorize([
    'amount' => 10.00,
    'currency' => 'EUR',
    'success_url' => 'https://website/success/{payment_id}',
    'failure_url' => 'https://website/failure/{payment_id}',
    'notification_url' => 'https://website/notification/{payment_id}',
])->send();

if (!$response->isSuccessful()) {
    throw new \RuntimeException('Error with payment');
}

$paymentId = $response->getPaymentId(); // use this for the next call

redirect($response->getRedirectUrl());

```

This call will return the `AuthorizeResponse` object. If the call is succesful then you can redirect the customer to the `redirectUrl`. Depending on the result in the next screen the customer will be redirected to the success or failure url.

### Capture
Before you can capture the payment, make sure the payment is successfully authorized using the `fetchTransaction` call.

```php
<?php

$gateway = Omnipay::create('Paysafecard');
$gateway->setApiKey('yourApiKey');

$response = $gateway->fetchTransaction([
    'payment_id' = $paymentId
])->send();

if ($response->getStatus === 'AUTHORIZED') {
    $captureResponse = $gateway->capture([
        'payment_id' = $paymentId
    ])->send();
}

```

### Refund
[Optional] You can call validateRefund first. Quoted from Paysafecard: *"In order to make sure that the requested refund can continue, the business partner has to precheck the likeliness of the upcoming refund to be successful, there are certain conditions why a refund might be refused"*
 ```php
<?php

$gateway = Omnipay::create('Paysafecard');
$gateway->setApiKey('yourApiKey');

$validationResponse = $gateway->validateRefund([
    'payment_id' => $paymentId,
    'amount' => 10.00,
    'currency' => 'EUR',
    'customer_email' => 'test@email.com',
    'customer_id' => 1001,
])->send();

if (!$validationResponse->getStatus() != 'VALIDATION_SUCCESSFUL') {
    throw new \RuntimeException('Error with refund validation');
}

$refundResponse = $gateway->refund([
  'payment_id' => $paymentId,
  'refund_id' => $validationResponse->getRefundId(),
])->send();

if ($refundResponse->isSuccessful()) {
    // refund was successful
}

```

or refund directly

```php
<?php

$gateway = Omnipay::create('Paysafecard');
$gateway->setApiKey('yourApiKey');

$refundResponse = $gateway->refund([
    'payment_id' => $paymentId,
    'amount' => 10.00,
    'currency' => 'EUR',
    'customer_email' => 'test@email.com',
    'customer_id' => 1001,
])->send();

if ($refundResponse->isSuccessful()) {
    // refund was successful
}

```

## Tests
Run the unit tests with `composer run test`

## Support

If you are having general issues with Omnipay, we suggest posting on
[Stack Overflow](http://stackoverflow.com/). Be sure to add the
[omnipay tag](http://stackoverflow.com/questions/tagged/omnipay) so it can be easily found.

If you believe you have found a bug, please report it using the [GitHub issue tracker](https://github.com/worldstream-labs/omnipay-paysafecard/issues),
or better yet, fork the library and submit a pull request.

## References
[Paysafecard REST API docs](https://www.paysafecard.com/fileadmin/api/#/reference/payment-process/initiating-a-payment?console=1)
[Omnipay Mollie v3 (similar JSON API)](https://github.com/thephpleague/omnipay-mollie) (official package, use as template)
[Omnipay Paysafecard Rest (Omnipay v2)](https://github.com/sauladam/omnipay-paysafecard-rest)