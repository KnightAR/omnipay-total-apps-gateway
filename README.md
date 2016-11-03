# Omnipay: Total Apps Gateway

**Total Apps Gateway gateway for the Omnipay PHP payment processing library**

[Omnipay](https://github.com/thephpleague/omnipay) is a framework agnostic, multi-gateway payment
processing library for PHP 5.3+. This package implements Total Apps Gateway support for Omnipay.

## Install

Via Composer

``` bash
$ composer require awolacademy/omnipay-total-apps-gateway
```

## Usage

The following gateways are provided by this package:

 * Total Apps Gateway

For general usage instructions, please see the main [Omnipay](https://github.com/thephpleague/omnipay) repository.

This driver supports following transaction types:

- authorize($options) - authorize an amount on the customer's card
- capture($options) - capture an amount you have previously authorized
- purchase($options) - authorize and immediately capture an amount on the customer's card
- refund($options) - refund an already processed transaction
- void($options) - generally can only be called up to 24 hours after submitting a transaction

Gateway instantiation:
``` PHP
    $gateway = Omnipay::create('TotalAppsGateway');
    $gateway->setProcessorId('abcdefg1234567');
    $gateway->setToken('6ef44f261a4a1595cd377d3ca7b57b92');
    $gateway->setTestMode(true);
```

Driver also supports paying using store cards in the customer vault using `cardReference` instead of `card`, 
use the vault functions with the `cardReference` parameter.

This driver also supports storing customer data in Total Apps Gateway's customer vault:

- createCard($options) - Create a entry in the customer vault
- updateCard($options) - Update an entry in the customer vault
- deleteCard($options) - Delete an entry in a customer vault
``` PHP
    $formData = array('number' => '4242424242424242', 'expiryMonth' => '8', 'expiryYear' => '2017', 'cvv' => '123');
    
    $response = $gateway->createCard([
        'card'          => $formData
    ])->send();
    
    $cardReference = $response->getCardReference();
```
- listCards - Listing customer vault records by criteria
``` PHP
    # Each criteria are optional, no criteria will return no records
    $response = $gateway->listCards([
        'cardReference' => '', # The hash to identify the customer in the vault
        'firstName'     => '', # Portion of cardholder's first name.
        'lastName'      => '', # Portion of cardholder's last name.
        'email'         => '', # Portion of billing email address.
        'last4cc'       => ''  # Last 4 digits of credit card number.
    ]);
    $response_rows = $response->getResponse();
```

`cardReference` can be used in the authorize, purchase, and refund requests:
``` PHP
    $gateway->purchase([
        'amount'        => '10.00',
        'cardReference' => '1234567890'
    ]);
```
This driver also supports subscription management which can be accessed using:
 
- subscription_add($options) - Add a subscription
- subscription_delete($options) - Delete a subscription
``` PHP
    # As an example we will add a subscription the starts on 01/04/2017
    $gateway->subscription_add([
        'cardReference'          => '1234567890',
        'planId'                 => '1234567890',
        'subscriptionStartDay'   => '01',
        'subscriptionStartMonth' => '04',
        'subscriptionStartYear'  => '2017'
    ]);
```

API Calls on the TODO list which will be implemented eventually: 

- Adding, updating, removing, listing Recurring Plans
- Listing subscriptions by customer
- Add a Customer to the Vault while Initiating a Sale/Authorization/Credit/Validate Transaction

We currently have no plans to implement the following calls (Pull requests are accepted for those who wants to add them):

- Adding a custom subscription - Does not return necessary subscription ID to cancel
- Adding a customer and subscription - Does not return necessary subscription ID to cancel

Note: Credit API call is implemented but is not enabled by default on merchant accounts,
      contact Total Apps Gateway if you need this functionality. Please note that this code is untested.

## Support

If you are having general issues with Omnipay, we suggest posting on
[Stack Overflow](http://stackoverflow.com/). Be sure to add the
[omnipay tag](http://stackoverflow.com/questions/tagged/omnipay) so it can be easily found.

If you want to keep up to date with release anouncements, discuss ideas for the project,
or ask more detailed questions, there is also a [mailing list](https://groups.google.com/forum/#!forum/omnipay) which
you can subscribe to.

If you believe you have found a bug, please report it using the [GitHub issue tracker](https://github.com/awolacademy/omnipay-total-apps-gateway/issues),
or better yet, fork the library and submit a pull request.

## Change log

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.

## Testing

``` bash
$ composer test
```

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Security

If you discover any security related issues, please email jablonski.kce@gmail.com instead of using the issue tracker.

## Credits

- [John Jablonski](https://github.com/jan-j)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
